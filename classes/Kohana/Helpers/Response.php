<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */
  
class Kohana_Helpers_Response
{
	static function json($data = NULL, $httpCode = 200, $headers = NULL)
	{
		$response = Kohana_Response::factory();
		$response->headers([
			'cache-control' => 'no-cache, no-store, max-age=0, must-revalidate',
			'content-type' => 'application/json; charset=utf-8',
		]);
		if (Kohana_Arr::is_array($headers)) $response->headers($headers);
		try {
			$response->status($httpCode);
		} catch (Exception $e) {
			$response->status($httpCode = 500);
			$data = $e;
		}

		if ($data instanceof Exception) {
			if ($data instanceof HTTP_Exception) {
				$response->status($httpCode = $data->getCode());
			} elseif ($httpCode < 500) {
				$response->status($httpCode = 500);
			}
			$data = self::exceptionAsArray($data);
		}

		if (NULL === $data && $httpCode == 200) {
			$response->status(204);
		} elseif (NULL !== $data) {
			try {
				$response->body(json_encode($data, JSON_UNESCAPED_UNICODE));
			} catch (Exception $e) {
				$response->body(json_encode(self::exceptionAsArray($e), JSON_UNESCAPED_UNICODE), 500);
			}
		}

		$response->send_headers(TRUE);

		exit($response);
	}

	public static function exceptionAsArray(Exception $e = NULL)
	{
		if ($e instanceof Exception) {
			$data = [
				'message' => $e->getMessage(),
				'code' => $e->getCode(),
			];

			if (!Kohana_Helpers_Core::isProduction()) {
				$data = Kohana_Helpers_Arr::merge($data, [
					'file' => $e->getFile(),
					'line' => $e->getLine(),
					'trace' => $e->getTrace(),
					'previous' => self::exceptionAsArray($e->getPrevious()),
				]);
			}
		} else {
			$data = NULL;
		}

		return $data;
	}

}
