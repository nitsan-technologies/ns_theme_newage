<?php

// Provide detailed information and depenencies of EXT:ns_theme_newage
$EM_CONF['ns_theme_newage'] = [
	'title' => 'T3 New Age – TYPO3 App Landing Template',
	'description' => 'A modern TYPO3 landing page template designed for showcasing mobile apps, SaaS products, or web platforms. Built with Bootstrap 3, HTML5, CSS3, and SASS for performance and flexibility.',
	'category' => 'templates',
	'author' => 'Team T3Planet',
	'author_email' => 'info@t3planet.de',
	'author_company' => 'T3Planet',
	'state' => 'stable',
	'version' => '13.0.0',
	'constraints' => [
		'depends' => [
			'typo3' => '13.0.0-13.4.99',
			'ns_basetheme' => '12.0.0-13.4.99',
		],
		'conflicts' => [
		],
		'suggests' => [
		],
	],
	'autoload' => array(
		'classmap' => array('Classes/'),
	),
];
