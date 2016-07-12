<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['extTablesInclusion-PostProcessing']['be_links'] =
        'EXT:be_links/Classes/Hook/BootstrapHook.php:CPSIT\\BeLinks\\Hook\\BootstrapHook';
}
