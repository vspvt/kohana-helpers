<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */

class Kohana_Helpers_Arr
{
	/**
	 * Checks if a value exists in an array
	 *
	 * @param mixed $needle   The searched value
	 * @param mixed $haystack The array
	 * @param bool  $strict   [optional]
	 *
	 * @return bool true if needle is found in the array, false otherwise.
	 */

	public static function inArray($needle, $haystack, $strict = NULL)
	{
		return Kohana_Arr::is_array($haystack) && in_array($needle, $haystack, $strict);
	}

	/**
	 * Counts all elements in an array, or something in an object.
	 *
	 * @param mixed $var
	 * @param int   $mode [optional]
	 *
	 * @return int
	 */
	public static function count($var, $mode = COUNT_NORMAL)
	{
		return Kohana_Arr::is_array($var) ? count($var, $mode) : 0;
	}

	/**
	 * @param        $var
	 * @param string $delimeter [optional]
	 *
	 * @return array
	 */
	public static function asArray($var, $delimeter = ',')
	{
		if (Kohana_Arr::is_array($var)) {
			return $var;
		}
		try {
			if (NULL === $var) {
				$result = [];
			} else {
				$var = trim((string) $var);
				$result = strlen($var) ? preg_split('#' . $delimeter . '#i', $var, -1, PREG_SPLIT_NO_EMPTY) : [];
			}
		} catch (Exception $e) {
			$result = [];
		}

		return $result;
	}

	/**
	 * @return array
	 */
	public static function merge()
	{
		$result = [];
		for ($i = 0; $i < func_num_args(); $i++) {
			$result = Kohana_Arr::merge($result, self::asArray(func_get_arg($i)));
		}

		return $result;
	}

	/**
	 * @param     $data
	 * @param int $default
	 *
	 * @return mixed
	 */
	public static function sum($data, $default = 0)
	{
		return Kohana_Arr::is_array($data) ? array_sum($data) : $default;
	}

	/**
	 * @param array  $array
	 * @param string $keyPrefix
	 * @param string $delimeter
	 * @param bool   $trimAsNULL
	 *
	 * @return array
	 */
	public static function flattenExtended($array, $keyPrefix = '', $delimeter = '.', $trimAsNULL = TRUE)
	{
		$keyPrefix = trim($keyPrefix);
		$flat = [];
		foreach ($array as $key => $value) {
			$newKey = $keyPrefix . $key;
			if (Kohana_Arr::is_array($value)) {
				$flat[Valid::digit($newKey) ? (int) $newKey : $newKey] = $value;
				$flat = self::merge($flat, self::flattenExtended($value, $newKey . $delimeter, $delimeter));
			} else {
				$flat[Valid::digit($newKey)
					? (int) $newKey
					: $newKey
				] = $trimAsNULL
					? Helpers_Text::trimAsNULL($value)
					: $value
				;
			}
		}

		return $flat;
	}

	/**
	 * @param Exception $e
	 *
	 * @return array|null
	 */
	public static function exception(Exception $e = NULL)
	{
		if ($e instanceof Exception) {
			$data = [
				'message' => Helpers_Text::trimAsNULL($e->getMessage()),
				'code' => $e->getCode(),
			];

			if (!Kohana_Helpers_Core::isProduction()) {
				$data = Kohana_Helpers_Arr::merge($data, [
					'file' => $e->getFile(),
					'line' => $e->getLine(),
					'trace' => $e->getTrace(),
					'previous' => Helpers_Arr::exception($e->getPrevious()),
				]);
			}
		} else {
			$data = NULL;
		}

		return $data;
	}

}
