<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */
  
abstract class Kohana_Helpers
{
	/** @var array */
	protected static $_configData;
	protected static $_configKey = 'helpers';

	protected static function config($path, $default = NULL, $delimeter = NULL)
	{
		if (!isset(static::$_configData)) {
			static::$_configData = Kohana::$config->load('helpers')->as_array();
		}

		return Kohana_Arr::path(static::$_configData, $path, $default, $delimeter);
	}
}
