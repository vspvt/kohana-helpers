<?php
/**
 * @author: Vad Skakov <vad.skakov@gmail.com>
 */
return [
	'cache' => [
		'log' => [
			'exceptions' => FALSE // FALSE or set Log::{level}
		]
	],
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
	]
];
