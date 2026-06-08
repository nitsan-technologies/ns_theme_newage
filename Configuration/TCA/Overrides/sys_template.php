<?php



defined('TYPO3') or die();

$_EXTKEY = 'ns_theme_newage';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(

    $_EXTKEY,
    'Configuration/TypoScript',
    'TYPO3 New Age Template'

);