<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */

class Kohana_Helpers_Request
{
	use Kohana_HelpersConfig;

	const POST = Request::POST;
	const GET = Request::GET;
	const PUT = Request::PUT;
	const DELETE = Request::DELETE;
	const PATCH = 'PATCH';
	const OPTIONS = Request::OPTIONS;

	static protected $parsedData;
	static protected $cachedData;
	static protected $method;

	/**
	 * @return Request
	 */
	public static function current()
	{
		return Request::current();
	}

	/**
	 * @param bool $override
	 *
	 * @return string
	 */
	public static function method($override = FALSE)
	{
		$request = static::current();

		if (is_bool($override)) {
			$method = Kohana_Arr::get(
				$_SERVER,
				'HTTP_X_HTTP_METHOD_OVERRIDE',
				$request->method()
			);
		} else {
			$method = $override;
		}

		FALSE === $override or $request->method($method);

		return $method;
	}

	/**
	 * @return array
	 */
	public static function getMethodsAllowed()
	{
		return Kohana_Helpers_Arr::asArray(static::config('request.methods.allowed'));
	}

	/**
	 * @return array
	 */
	public static function getMethodsWithBody()
	{
		return Kohana_Helpers_Arr::asArray(static::config('request.methods.withBody'));
	}

	/**
	 * @return array
	 */
	public static function getMethodsParseBody()
	{
		return Kohana_Helpers_Arr::asArray(static::config('request.methods.parseBody'));
	}

	/**
	 * @param null|string $methodName
	 * @param null|array  $methodsList
	 *
	 * @return bool
	 */
	public static function isMethodAllowed($methodName = NULL, $methodsList = NULL)
	{
		NULL !== $methodName or $methodName = static::method(FALSE);
		is_array($methodsList) or $methodsList = static::getMethodsAllowed();

		return Kohana_Helpers_Arr::inArray($methodName, $methodsList);
	}

	/**
	 * @param null|string $methodName
	 * @param null|array  $methodsList
	 *
	 * @return bool
	 */
	public static function isMethodWithBody($methodName = NULL, $methodsList = NULL)
	{
		NULL !== $methodName or $methodName = static::method(FALSE);
		is_array($methodsList) or $methodsList = static::getMethodsWithBody();

		return Kohana_Helpers_Arr::inArray($methodName, $methodsList);
	}

	/**
	 * @param null|string $methodName
	 * @param null|array  $methodsList
	 *
	 * @return bool
	 */
	public static function isMethodParseBody($methodName = NULL, $methodsList = NULL)
	{
		NULL !== $methodName or $methodName = static::method(FALSE);
		is_array($methodsList) or $methodsList = static::getMethodsParseBody();

		return Kohana_Helpers_Arr::inArray($methodName, $methodsList);
	}

	/**
	 * @param string $key
	 * @param null   $default
	 * @param null   $castType
	 * @param bool   $nullable
	 *
	 * @return mixed
	 */
	static protected function _getQuery($key, $default = NULL, $castType = NULL, $nullable = TRUE)
	{
		$value = static::current()->query($key);
		if (NULL === $value || is_scalar($value)) {
			NULL !== $value or $value = $default;
			$value = Helpers_Text::setType($value, $castType, $nullable);
		}

		return $value;
	}

	/**
	 * @param string|array $key
	 * @param null         $default
	 * @param null         $castType
	 * @param bool         $nullable
	 *
	 * @return array|mixed
	 * @throws InvalidArgumentException
	 */
	public static function getQuery($key, $default = NULL, $castType = NULL, $nullable = TRUE)
	{
		if (!is_scalar($key) && !is_array($key)) {
			throw new InvalidArgumentException('$key must be scalar or array');
		}

		if (is_scalar($key)) {
			$result = static::_getQuery($key, $default, $castType, $nullable);
		} else {
			$isAssoc = Kohana_Arr::is_assoc($key);
			$result = [];
			foreach ($key as $keyName => $keyData) {
				$resultKey = $keyData;
				if ($isAssoc) {
					$queryKey = $keyName;
				} else {
					$queryKey = $keyData;
				}
				$result[$resultKey] = static::_getQuery($queryKey, $default, $castType, $nullable);
			}
		}

		return $result;
	}

	public static function data($pathKey = NULL, $pathDefault = NULL, $order = NULL, $forced = FALSE)
	{
		$order = Helpers_Text::trimAsNULL(NULL === $order ? $order = static::config('request.data.order') : $order);

		$data = [];
		if (NULL !== $order) {
			isset(static::$cachedData) or static::$cachedData  = [];
			if ($forced || !isset(static::$cachedData[$order])) {
				for ($idx=0; $idx < strlen($order); $idx++) {
					switch (strtoupper($order[$idx])) {
						case 'G':
							$data = Kohana_Helpers_Arr::merge($data, static::current()->query());
							break;
						case 'P':
							$data = Kohana_Helpers_Arr::merge($data, static::getBodyData());
							break;
						case 'H':
							$data = Kohana_Helpers_Arr::merge($data, static::current()->headers());
							break;
					}
				}

				static::$cachedData[$order] = $data;
			}
		}

		if (is_array($pathKey)) {
			$result = [];
			foreach ($pathKey as $key) {
				$result[$key] = Kohana_Helpers_Arr::path($data, $pathKey, $pathDefault);
			}
		} elseif (is_scalar($pathKey)) {
			$result = Kohana_Helpers_Arr::path($data, $pathKey, $pathDefault);
		} else {
			$result = $data;
		}

		return $result;
	}

	/**
	 * @return array
	 */
	public static function getBodyData()
	{
		$data = [];
		if (static::isMethodWithBody()) {
			if (static::isMethodParseBody()) {
				$data = static::getParsedData();
			} else {
				$data = static::current()->post();
			}
		}

		return $data;
	}

	/**
	 * @throws Exception_Request_InvalidJSON
	 * @return array
	 */
	public static function getParsedData()
	{
		if (!isset(static::$parsedData)) {
			static::$parsedData = [];

			$body = static::current()->body();
			if (strlen($body)) {
				if (FALSE !== strpos($_SERVER['CONTENT_TYPE'], 'application/json')) {
					$data = json_decode($body, TRUE);
					if (NULL === $data) throw new Exception_Request_InvalidJSON;
				} else {
					$data = static::parseMultiPartContent($body);
				}

				static::$parsedData = Helpers_Arr::asArray($data);
			}
		}

		return static::$parsedData;
	}

	/**
	 * @param null|string $input
	 * @param null|bool   $parseFiles
	 *
	 * @return array
	 */
	public static function parseMultiPartContent($input = NULL, $parseFiles = NULL)
	{
		NULL !== $input or $input = static::current()->body();
		NULL !== $parseFiles or $parseFiles = static::config('request.data.parseBodyFiles.enabled', FALSE);

		$data = [];
		$files = $parseFiles ? [] : NULL;

		// grab multipart boundary from content type header
		preg_match('/boundary=["]*([^"\s;]+)/', $_SERVER['CONTENT_TYPE'], $matches);

		if (count($matches)) {
			$boundary = preg_quote($matches[1]);

			// split content by boundary and get rid of last -- element
			$blocks = preg_split("/-+$boundary/", $input);

			// loop data blocks
			foreach ($blocks as $block) {
				if (empty($block))
					continue;

				$parts = preg_split('/[\r\n][\r\n]/', trim($block, "\r\n"), 2, PREG_SPLIT_NO_EMPTY);

				if (count($parts) != 2) continue;

				list($raw_headers, $input) = $parts;

				if (preg_match(
					'/name="([^"]+)"(; *filename="([^"]+)")?/',
					$raw_headers,
					$matches
				)) {
					$name = rawurldecode($matches[1]);
					$filename = isset($matches[3]) ? $matches[3] : NULL;

					if (!isset($filename)) {
						$input = Helpers_Text::trimAsNULL($input);
						$_tmp = "{$name}={$input}";
						$_data = NULL;
						parse_str($_tmp, $_data);
						$data = Helpers_Arr::merge($data, $_data);
					} elseif (isset($files)) {
						$_tmpname = tempnam(NULL, 'tmp');
						if (FALSE !== $_tmpname) {
							if (preg_match('@^Content-Type:@im', $input)) {
								$input = trim(preg_replace('@^Content-Type:[^\n]*@i', "", $input), "\r\n");
							}

							file_put_contents($_tmpname, $input);
							chmod($_tmpname, static::config('request.data.parseBodyFiles.chmod', 0666));

							$files[$name] = [
								'name' => $filename,
								'type' => mime_content_type($_tmpname),
								'tmp_name' => $_tmpname,
								'error' => UPLOAD_ERR_OK,
								'size' => filesize($_tmpname),
							];
						}
					}
				}
			}

			if (Kohana_Helpers_Arr::count($files)) {
				foreach ($files as $fileKey=>$fileInfo) {
					$_FILES[$fileKey] = $fileInfo;
				}

				if (static::config('request.data.parseBodyFiles.deleteOnShutdown', TRUE)) {
					register_shutdown_function(function () use ($files) {
						foreach ($files as $row) @unlink($row['tmp_name']);
					});
				}
			}
		}

		return $data;
	}


}
