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
	 * @param mixed $array1
	 * @param mixed $array2
	 *
	 * @return array
	 */
	public static function merge($array1, $array2)
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

}
