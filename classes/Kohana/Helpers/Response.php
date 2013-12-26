<?php

/**
 * Class Kohana_Helpers_Response
 */
class Kohana_Helpers_Response
{
	/**
	 * @param null       $data
	 * @param int        $httpCode
	 * @param null|array $headers
	 *
	 * @throws HTTP_Exception_Redirect
	 */
	static function json($data = NULL, $httpCode = 200, $headers = NULL)
	{
		if ($data instanceof HTTP_Exception_Redirect) {
			throw $data;
		}

		$response = Kohana_Response::factory();
		try {
			$response->headers([
				'cache-control' => 'no-cache, no-store, max-age=0, must-revalidate',
				'content-type' => 'application/json; charset=utf-8',
			]);
			if (Kohana_Arr::is_array($headers)) $response->headers($headers);

			$response->status($httpCode);
		} catch (Exception $e) {
			$response->status($httpCode = 500);
			$data = $e;
		}

		if ($data instanceof Exception) {
			if ($data instanceof HTTP_Exception) {
				$response->status($httpCode = $data->getCode());
			} elseif ($httpCode < 400) {
				$response->status($httpCode = 500);
			}
			$data = Helpers_Arr::exception($data);
		}

		if (NULL === $data && $httpCode == 200) {
			$response->status(204);
		} elseif (NULL !== $data) {
			try {
				$response->body(json_encode($data, JSON_UNESCAPED_UNICODE));
			} catch (Exception $e) {
				$response->body(json_encode(Helpers_Arr::exception($e), JSON_UNESCAPED_UNICODE), 500);
			}
		}

		$response->send_headers(TRUE);

		exit($response);
	}

	/**
	 * @param Exception  $e
	 * @param int        $httpCode
	 * @param null|array $headers
	 */
	public static function exception(Exception $e, $httpCode = 500, $headers = NULL)
	{
		self::json(Helpers_Arr::exception($e), $httpCode, $headers);
	}

}
