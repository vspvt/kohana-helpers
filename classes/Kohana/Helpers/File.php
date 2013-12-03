<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */

class Kohana_Helpers_File
{
	/**
	 * Recursive directory delete
	 *
	 * @param      $dir
	 * @param bool $deleteRoot
	 * @param bool $stopOnError
	 * @param      $debug
	 *
	 * @return bool
	 */
	static function rmdir($dir, $deleteRoot = TRUE, $stopOnError = TRUE, &$debug = NULL)
	{
		if (is_dir($dir)) {
			if (NULL === $debug) $debug = [];
			try {
				foreach (glob($dir . '/*') as $file) {
					if (is_dir($file)) {
						self::rmdir($file, TRUE, $stopOnError, $debug);
					} else {
						$debug[] = printf("File: %s", Debug::path($file));
					}
					unlink($file);
				}
				if ($deleteRoot) {
					$debug[] = printf("Dir: %s", Debug::path($dir));
					rmdir($dir);
				}

				return TRUE;
			} catch (Exception $e) {
				if ($stopOnError) {
					$debug[] = $e->getMessage();
					return FALSE;
				}
			}
		}

		return FALSE;
	}

}
