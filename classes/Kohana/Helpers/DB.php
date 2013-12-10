<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */

class Kohana_Helpers_DB
{
	protected static $transactions = [];

	protected static function instanceName($instance = NULL)
	{
		return NULL === $instance ? Database::$default : $instance;
	}

	protected static function _instance($instance = NULL)
	{
		if (!class_exists('Kohana_Database')) {
			throw new Exception('Kohana_Database module not loaded');
		}
		$instance = self::instanceName($instance);

		if (!array_key_exists($instance, static::$transactions)) {
			static::$transactions[$instance] = FALSE;
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
		$object = self::_instance($instance);
		$instance = self::instanceName($instance);

		if (static::$transactions[$instance]) return FALSE;
		static::$transactions[$instance] = $object->begin($mode);

		return static::$transactions[$instance];
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
