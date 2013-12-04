<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */

class Kohana_Helpers_CLI extends Kohana_Helpers
{
	protected static $dateFormat = 'd.m.Y H:i:s';
	protected static $dateSuffix = ' ';
	protected static $logWrite = FALSE;
	protected static $logReplace = FALSE;

	/**
	 * @return bool
	 */
	protected static function classExists()
	{
		return class_exists('Kohana_Minion_Cli');
	}

	/**
	 * @return string
	 */
	protected static function date()
	{
		$format = self::config('cli.date.format', static::$dateFormat);

		return is_string($format) ? date($format) . self::config('cli.date.suffix', static::$dateSuffix) : '';
	}

	/**
	 * @param string     $message
	 * @param null|array $args
	 * @param bool       $output
	 */
	static function write($message, $args = NULL, $output = TRUE)
	{
		if (Kohana_Helpers_Core::isCLI() && self::classExists()) {
			$args = Kohana_Helpers_Arr::asArray($args);
			if ($output) {
				Kohana_Minion_CLI::write(self::date() . strtr($message, $args));
			} elseif (FALSE !== $logLevel = self::config('cli.log.write', static::$logWrite)) {
				Kohana::$log->add($logLevel, $message, $args);
			}
		}
	}

	/**
	 * @param string     $message
	 * @param null|array $args
	 * @param bool       $end_line
	 * @param bool       $output
	 */
	static function writeReplace($message, $args = NULL, $end_line = FALSE, $output = TRUE)
	{
		if (Kohana_Helpers_Core::isCLI() && self::classExists()) {
			$args = Kohana_Helpers_Arr::asArray($args);
			if ($output) {
				Kohana_Minion_CLI::write_replace(self::date() . strtr($message, $args), $end_line);
			} elseif (FALSE !== $logLevel = self::config('cli.log.replace', static::$logReplace)) {
				Kohana::$log->add($logLevel, $message, $args);
			}
		}
	}

}
