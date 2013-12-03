<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */
  
class Kohana_Helpers_Core
{
	/**
	 * @return bool
	 */
	public static function isCLI()
	{
		return PHP_SAPI == 'cli';
	}

	/**
	 * @return bool
	 */
	public static function isProduction()
	{
		return Kohana::$environment === Kohana::PRODUCTION;
	}

	/**
	 * @return bool
	 */
	public static function isDevelopment()
	{
		return Kohana::$environment === Kohana::DEVELOPMENT;
	}

	/**
	 * @return bool
	 */
	public static function isStaging()
	{
		return Kohana::$environment === Kohana::STAGING;
	}

	/**
	 * @return bool
	 */
	public static function isTesting()
	{
		return Kohana::$environment === Kohana::TESTING;
	}

}
