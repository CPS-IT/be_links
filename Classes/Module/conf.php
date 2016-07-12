<?php

$GLOBALS['moduleArray'] = \CPSIT\BeLinks\Utility\ModuleUtility::getModuleArray(\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('M'));
$MCONF = \CPSIT\BeLinks\Utility\ModuleUtility::getDefaultModuleConfiguration($GLOBALS['moduleArray']);
