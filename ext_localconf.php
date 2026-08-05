<?php
$_EXTKEY = "ns_theme_newage";

$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:ns_theme_newage/Configuration/RTE/Default.yaml';

$GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['ns_theme_newage'] =
    'EXT:ns_theme_newage/Resources/Public/Backend/Css/Backend.css';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig("@import 'EXT:ns_theme_newage/Configuration/PageTSconfig/setup.typoscript'");
