<?php

// Provide detailed information and depenencies of EXT:ns_theme_newage
$EM_CONF['ns_theme_newage'] = [
	'title' => '[NITSAN] T3 New Age TYPO3 Template',
	'description' => 'T3 New Age Landing TYPO3 Template is a Bootstrap minimal app landing page. This template has been designed for any need to showcase app or website, app landing page. Built it with twitter bootstrap 3, HTML5, CSS3 and SASS. Live-Demo: https://demo.t3terminal.com/?theme=t3t-newage Pro-Version & Support: https://t3terminal.com/t3-new-age-landing-typo3-template',
	'category' => 'templates',
	'author' => 'Team NITSAN',
	'author_email' => 'info@nitsan.in',
	'author_company' => 'NITSAN Technologies Pvt Ltd',
	'state' => 'stable',
	'version' => '4.0.3',
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
