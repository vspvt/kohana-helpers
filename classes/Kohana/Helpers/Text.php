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

}
