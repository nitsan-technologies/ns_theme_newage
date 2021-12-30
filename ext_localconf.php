<?php
$_EXTKEY = "ns_theme_newage";

$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:ns_theme_newage/Configuration/RTE/Default.yaml';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig("@import 'EXT:ns_theme_newage/Configuration/PageTSconfig/setup.typoscript'");
