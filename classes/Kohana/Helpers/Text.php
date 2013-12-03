<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */

class Kohana_Helpers_Text extends Text
{
	/**
	 * @param      $value
	 * @param      $charlist
	 * @param bool $utf8
	 *
	 * @return null|string
	 */
	public static function trimAsNULL($value, $charlist = NULL, $utf8 = TRUE)
	{
		if (NULL !== $value) {
			if ($utf8) {
				$value = Kohana_UTF8::trim($value, $charlist);
				if (!Kohana_UTF8::strlen($value)) $value = NULL;
			} else {
				$value = trim($value, $charlist);
				if (!strlen($value)) $value = NULL;
			}
		}

		return $value;
	}

	/**
	 * @param      $value
	 * @param      $type
	 * @param bool $nullable
	 *
	 * @return mixed
	 */
	public static function setType($value, $type, $nullable = TRUE)
	{
		if (!$nullable || NULL !== $value) {
			settype($value, $type);
		}

		return $value;
	}

	/**
	 * @param      $value
	 * @param bool $nullable
	 *
	 * @return int|null
	 */
	public static function setTypeInt($value, $nullable = TRUE)
	{
		return self::setType($value, 'int', $nullable);
	}

	/**
	 * @param      $value
	 * @param bool $nullable
	 *
	 * @return float|null
	 */
	public static function setTypeFloat($value, $nullable = TRUE)
	{
		return self::setType($value, 'float', $nullable);
	}

	/**
	 * @param      $value
	 * @param bool $asInt
	 * @param bool $nullable
	 *
	 * @return string|int|null
	 */
	public static function digits($value, $asInt = FALSE, $nullable = TRUE)
	{
		$value = preg_replace('/\D+/', '', (string) $value);
		if ($nullable && !strlen($value)) $value = NULL;

		return $asInt ? self::setTypeInt($value, $nullable) : $value;
	}

	/**
	 * Возвращает слово во множестевенном/единственном числе на основании $n
	 *
	 * @param int         $n
	 * @param string      $one
	 * @param null|string $two
	 * @param null|string $five
	 *
	 * @return string
	 */
	public static function plural($n, $one, $two = NULL, $five = NULL)
	{
		if (is_array($one)) {
			$values = [Kohana_Arr::get($one, 0)];
			$values[1] = Kohana_Arr::get($one, 1, $values[0]);
			$values[2] = Kohana_Arr::get($one, 2, $values[1]);
		} else {
			$values = [$one];
			$values[1] = NULL !== $two ? $two : $one;
			$values[2] = NULL !== $five ? $five : $values[1];
		}

		$idx = $n % 10 == 1 && $n % 100 != 11
			? 0
			: ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20)
				? 1
				: 2
			);

		return $values[$idx];
	}

	/**
	 * @param int         $n
	 * @param null|string $format
	 * @param string      $one
	 * @param null|string $two
	 * @param null|string $five
	 *
	 * @return string
	 */
	public static function pluralFormat($n, $format = NULL, $one, $two = NULL, $five = NULL)
	{
		if (NULL === $format) $format = ':number :plural';

		return strtr($format, [
			':number' => $n,
			':plural' => self::plural($n, $one, $two, $five)
		]);
	}

}
