<?php
// TYPO3 Security Check
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}
// Extension key
$_EXTKEY = 'ns_theme_newage';
// Add default include static TypoScript (for root page)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('ns_theme_newage', 'Configuration/TypoScript', 'New age');
