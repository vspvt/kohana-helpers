<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */

class Kohana_Helpers_DB
{
	protected static function _instance($instance = NULL)
	{
		if (!class_exists('Kohana_Database')) {
			throw new Exception('Kohana_Database module not loaded');
		}

		return Kohana_Database::instance($instance);
	}

	/**
	 * @param null $instance
	 * @param null $mode
	 *
	 * @return bool
	 */
	static function begin($instance = NULL, $mode = NULL)
	{
		return self::_instance($instance)->begin($mode);
	}

	/**
	 * @param null $instance
	 *
	 * @return bool
	 */
	static function commit($instance = NULL)
	{
		return self::_instance($instance)->commit();
	}

	/**
	 * @param null $instance
	 *
	 * @return bool
	 */
	static function rollback($instance = NULL)
	{
		return self::_instance($instance)->rollback();
	}

}
