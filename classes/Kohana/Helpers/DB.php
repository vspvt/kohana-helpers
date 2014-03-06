<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */

class Kohana_Helpers_DB
{
	protected static $transactions = [];

	/**
	 * @param null $name
	 *
	 * @return null|string
	 */
	protected static function instanceName($name = NULL)
	{
		return NULL === $name ? Database::$default : $name;
	}

	/**
	 * @param null $name
	 *
	 * @return Database
	 * @throws ErrorException
	 */
	protected static function _instance($name = NULL)
	{
		if (!class_exists('Kohana_Database')) {
			throw new ErrorException('Kohana_Database module not loaded');
		}
		$name = static::instanceName($name);

		if (!array_key_exists($name, static::$transactions)) {
			static::$transactions[$name] = FALSE;
		}

		return Kohana_Database::instance($name);
	}

	/**
	 * @param null $name
	 * @param null $mode
	 *
	 * @return bool
	 */
	static function begin($name = NULL, $mode = NULL)
	{
		$object = static::_instance($name);
		$name = static::instanceName($name);

		if (static::$transactions[$name]) return FALSE;
		static::$transactions[$name] = $object->begin($mode);

		return static::$transactions[$name];
	}

	/**
	 * @param null $name
	 *
	 * @return bool
	 */
	static function commit($name = NULL)
	{
		return static::_instance($name)->commit();
	}

	/**
	 * @param null $name
	 *
	 * @return bool
	 */
	static function rollback($name = NULL)
	{
		return static::_instance($name)->rollback();
	}

	/**
	 * @param callable $closure
	 * @param null     $name
	 * @param null     $mode
	 *
	 * @return mixed
	 * @throws Exception
	 */
	static function transaction(Closure $closure, $name = NULL, $mode = NULL)
	{
		try {
			$transactionStarted = static::begin($name, $mode);
			$result = call_user_func($closure);
			if ($transactionStarted) static::commit($name);

			return $result;
		} catch (Exception $e) {
			if (isset($transactionStarted) && $transactionStarted) static::rollback($name);
			throw $e;
		}
	}

}
