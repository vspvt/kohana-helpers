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
	]
];
