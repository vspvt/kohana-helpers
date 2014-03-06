<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */
  
trait Kohana_HelpersConfig
{
	/** @var array */
	protected static $_configData;
	protected static $_configKey = 'helpers';

	protected static function config($path, $default = NULL, $delimeter = NULL)
	{
		if (!isset(static::$_configData)) {
			static::$_configData = Kohana::$config->load(static::$_configKey)->as_array();
		}

		return Kohana_Arr::path(static::$_configData, $path, $default, $delimeter);
	}
}
