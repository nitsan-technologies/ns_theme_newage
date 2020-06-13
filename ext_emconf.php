<?php

// Provide detailed information and depenencies of EXT:ns_theme_newage
$EM_CONF['ns_theme_newage'] = [
    'title' => '[NITSAN] New Age',
    'description' => 'T3 New Age Landing TYPO3 Template is a Bootstrap minimal app landing page. This template has been designed for any need to showcase app or website, app landing page. Live-Demo: https://demo.t3terminal.com/?theme=t3t-newage Pro-Support: https://t3terminal.com/t3-new-age-landing-typo3-template',
    'category' => 'templates',
    'author' => 'NITSAN Technologies Pvt Ltd',
    'author_email' => 'info@nitsan.in',
    'author_company' => 'NITSAN Technologies Pvt Ltd',
    'state' => 'stable',
    'version' => '2.0.0',
    'constraints' => [
        'depends' => [
            'ns_basetheme' => '1.0.0-10.9.99',
            'gridelements' => '8.0.0-10.9.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    //'autoload' => array(
    //	'classmap' => array('Classes/'),
    //),
];
