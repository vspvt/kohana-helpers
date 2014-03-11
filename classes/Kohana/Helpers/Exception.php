<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */

class Kohana_Helpers_Exception extends Kohana_Exception implements JsonSerializable
{
	use Kohana_HelpersConfig;

	protected $code = 0;
	protected $message = 'helpers.exception';

	public function __construct($message = NULL, array $variables = NULL, $code = NULL, Exception $previous = NULL)
	{
		NULL !== $message or $message = $this->message;
		NULL !== $code or $code = $this->code;
		$variables = Kohana_Helpers_Arr::merge([
			':code' => $code,
			':text' => static::text($this),
		], $variables);

		parent::__construct($message, $variables, $code, $previous);
	}

	/**
	 * @param Exception|null $e
	 * @param array|null     $config
	 *
	 * @return array
	 */
	public static function toArray(Exception $e = NULL, array $config = NULL)
	{
		$data = [];

		if (NULL !== $e) {
			NULL !== $config or $config = (array) static::config('exception.toArray', []);

			foreach ($config as $configKey => $dataKey) {
				$dataValue = NULL;

				switch ($configKey) {
					case 'message':
						FALSE === $dataKey or $dataValue = Kohana_Helpers_Text::trim($e->getMessage());
						break;
					case 'code':
						FALSE === $dataKey or $dataValue = $e->getCode();
						break;
					case 'line':
						FALSE === $dataKey or $dataValue = $e->getLine();
						break;
					case 'file':
						FALSE === $dataKey or $dataValue = $e->getFile();
						break;
					case 'trace':
						$asString = Arr::get(Kohana_Helpers_Arr::asArray($dataKey), 'asString', FALSE);
						$dataKey = Arr::get(Kohana_Helpers_Arr::asArray($dataKey), 'key', 'trace');
						FALSE === $dataKey or $dataValue = $asString
							? $e->getTraceAsString()
							: $e->getTrace()
						;
						break;
					case 'previous':
						if (FALSE !== $dataKey) {
							$previous = $e->getPrevious();
							NULL === $previous or $dataValue = static::toArray($previous, $config);
							NULL !== $dataValue or $dataKey = FALSE;
						}
						break;
					default:
						$dataKey = FALSE;
				}

				if (FALSE !== $dataKey) {
					$data[$dataKey] = $dataValue;
				}
			}
		}

		return $data;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		return static::toArray($this);
	}

}
