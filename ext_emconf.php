<?php

// Provide detailed information and depenencies of EXT:ns_theme_newage
$EM_CONF['ns_theme_newage'] = [
    'title' => '[NITSAN] New Age',
    'description' => 'The child theme of EXT:ns_basetheme',
    'category' => 'templates',
    'author' => 'NITSAN Technologies Pvt Ltd',
    'author_email' => 'info@nitsan.in',
    'author_company' => 'NITSAN Technologies Pvt Ltd',
    'state' => 'stable',
    'version' => '3.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '9.0.0-10.9.99',
            'ns_basetheme' => '9.0.0-11.9.99',
            'gridelements' => '9.0.0-10.9.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],

];
