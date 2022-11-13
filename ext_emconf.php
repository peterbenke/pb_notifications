<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'Notifications',
	'description' => 'Manages notifications for editors in the backend',
	'category' => 'module',
	'author' => 'Peter Benke',
	'author_email' => 'info@typomotor.de',
	'state' => 'stable',
	'version' => '10.4.0',
	'constraints' => [
		'depends' => [
			'typo3' => '10.4.0-10.4.99',
		],
		'conflicts' => [],
		'suggests' => [],
	],
];