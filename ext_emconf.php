<?php

// Provide detailed information and depenencies of EXT:ns_theme_newage
$EM_CONF['ns_theme_newage'] = [
	'title' => '[NITSAN] T3 New Age | Free TYPO3 Template',
	'description' => 'T3 New Age Landing TYPO3 Template is a Bootstrap minimal app landing page. This template has been designed for any need to showcase app or website, app landing page. Built it with twitter bootstrap 3, HTML5, CSS3 and SASS.',
	'category' => 'templates',
	'author' => 'NITSAN Technologies Pvt Ltd',
	'author_email' => 'info@nitsan.in',
	'author_company' => 'NITSAN Technologies Pvt Ltd',
	'state' => 'stable',
	'version' => '4.0.0',
	'constraints' => [
		'depends' => [
			'typo3' => '10.0.0-11.5.99',
			'ns_basetheme' => '10.0.0-11.5.99',
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
