<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */
return [
	// Helpers_Cache
	'cache' => [
		'exception' => [
			'log' => FALSE, // FALSE or set Log::{level}
			'throw' => FALSE,
		],
	],
	// Helpers_CLI
	'cli' => [
		'date' => [
			'format' => 'd.m.Y H:i:s',
			'suffix' => ' ',
		],
		'log' => [
			'write' => FALSE, // FALSE or set Log::{level}
			'replace' => FALSE, // FALSE or set Log::{level}
		]
	],
	// Helpers_Request
	'request' => [
		'methods' => [
			'allowed' => [
				Helpers_Request::DELETE,
				Helpers_Request::GET,
				Helpers_Request::OPTIONS,
				Helpers_Request::PATCH,
				Helpers_Request::POST,
				Helpers_Request::PUT,
			],
			'withBody' => [
				Helpers_Request::PATCH,
				Helpers_Request::POST,
				Helpers_Request::PUT,
			],
			'parseBody' => [
				Helpers_Request::PATCH,
				Helpers_Request::PUT,
			]
		],
		'data' => [
			'order' => 'GP', // H=HEADERS, G=GET, P=POST|PUT|OPTIONS
			'parseBodyFiles' => [
				'enabled' => TRUE,
				'chmod' => 0666,
				'deleteOnShutdown' => TRUE,
			]
		],
	],
	// Helpers_Exception
	'exception' => [
		'toArray' => [
			'message' => 'message', // calls $e->getMessage()
			'code' => 'code', // calls $e->getCode()
			'line' => 'line', // calls $e->getLine()
			'file' => 'file', // calls $e->getFile()
			'previous' => 'previous', // calls $e->getPrevious()
			'trace' => [
				'key' => 'trace', // value must be string key of result array or FALSE
				'asString' => FALSE, // FALSE - calls $e->getTrace(), TRUE - calls $e->getTraceAsString()
			],
		],
	],
];
