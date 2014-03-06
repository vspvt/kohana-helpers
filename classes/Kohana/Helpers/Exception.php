<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */

class Kohana_Helpers_Exception extends Kohana_Exception
{
	protected $code = 0;
	protected $message = 'exception.request';

	public function __construct($message = NULL, array $variables = NULL, $code = NULL, Exception $previous = NULL)
	{
		NULL !== $message or $message = $this->message;
		NULL !== $code or $code = $this->code;

		parent::__construct($message, $variables, $code, $previous);
	}

}
