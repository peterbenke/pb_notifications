<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'Notifications',
	'description' => 'Manages notifications for editors in the backend',
	'category' => 'module',
	'author' => 'Peter Benke',
	'author_email' => 'info@typomotor.de',
	'state' => 'stable',
	'version' => '12.0.0-dev',
	'constraints' => [
		'depends' => [
			'typo3' => '12.4.19-12.4.99',
		],
		'conflicts' => [],
		'suggests' => [],
	],
];
