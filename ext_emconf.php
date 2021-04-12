<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'Notifications',
	'description' => 'Manages notifications for editors in the backend',
	'category' => 'module',
	'author' => 'Peter Benke',
	'author_email' => 'info@typomotor.de',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '3.0.3',
	'constraints' => [
		'depends' => [
			'typo3' => '9.5.0-10.4.99',
		],
		'conflicts' => [],
		'suggests' => [],
	],
];