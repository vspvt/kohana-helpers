<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */
  
class Kohana_Helpers_Cache
{
	use Kohana_HelpersConfig;

	/**
	 * @return bool
	 */
	protected static function classExists()
	{
		return class_exists('Cache') && class_exists('Cache_Exception');
	}

	/**
	 * @param null $group
	 *
	 * @return Cache
	 */
	protected static function instance($group = NULL)
	{
		return Cache::instance($group);
	}

	/**
	 * @param Exception $e
	 *
	 * @throws Exception
	 */
	protected static function exception(Exception $e)
	{
		$logLevel = self::config('cache.exception.log', FALSE);
		if (FALSE !== $logLevel) {
			Kohana_Exception::log($e, $logLevel);
		}

		if (self::config('cache.exception.throw', FALSE)) {
			throw $e;
		};
	}

	/**
	 * @param string      $key
	 * @param null|string $group
	 *
	 * @return bool
	 */
	public static function delete($key, $group = NULL)
	{
		$result = FALSE;
		try {
			!self::classExists() or $result = self::instance($group)->delete($key);
		} catch (Cache_Exception $e) {
			self::exception($e);
		}

		return $result;
	}

	/**
	 * @param null|string $group
	 *
	 * @return bool
	 */
	public static function deleteAll($group = NULL)
	{
		$result = FALSE;
		try {
			!self::classExists() or $result = self::instance($group)->delete_all();
		} catch (Cache_Exception $e) {
			self::exception($e);
		}

		return $result;
	}

	/**
	 * @param string      $key
	 * @param mixed       $default
	 * @param null|string $group
	 *
	 * @return mixed
	 */
	public static function get($key, $default = NULL, $group = NULL)
	{
		$result = $default;
		try {
			!self::classExists() or $result = self::instance($group)->get($key, $default);
		} catch (Cache_Exception $e) {
			self::exception($e);
		}

		return $result;
	}

	/**
	 * @param string      $key
	 * @param mixed       $data
	 * @param null|int    $lifetime
	 * @param null|string $group
	 *
	 * @return bool
	 */
	public static function set($key, $data, $lifetime = NULL, $group = NULL)
	{
		$result = FALSE;
		try {
			!self::classExists() or $result = self::instance($group)->set($key, $data, $lifetime);
		} catch (Cache_Exception $e) {
			self::exception($e);
		}

		return $result;
	}

}
