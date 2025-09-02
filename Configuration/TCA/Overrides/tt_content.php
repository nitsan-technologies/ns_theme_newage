<?php

declare(strict_types=1);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItemGroup(
    'tt_content', // table
    'CType', // typeField 
    'newage_content_blocks', // group
    'LLL:EXT:ns_theme_newage/Resources/Private/Language/locallang.xlf:contentBlocks.groupName', // label
    'before:default', // position
);