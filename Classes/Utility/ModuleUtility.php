<?php
namespace CPSIT\BeLinks\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Nicole Cordes <cordes@cps-it.de>, CPS-IT GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

final class ModuleUtility
{
    /**
     * @var array
     */
    protected static $authenticationArray = array(
        0 => '',
        1 => 'user,group',
        2 => 'admin',
        3 => 'user',
        4 => 'group',
    );

    /**
     * @var \TYPO3\CMS\Core\Imaging\GraphicalFunctions
     */
    protected static $graphicalFunctions = null;

    /**
     * @param array $moduleArray
     * @return string
     */
    public static function getModuleSignature($moduleArray)
    {
        $moduleSignature = 'BeLinksTxbelinksmodule' . $moduleArray['uid'];
        if (!empty($moduleArray['parent'])) {
            $moduleSignature = $moduleArray['parent'] . '_' . $moduleSignature;
        }

        return $moduleSignature;
    }

    /**
     * @param string $moduleSignature
     * @return array
     */
    public static function getModuleArray($moduleSignature)
    {
        if (strpos($moduleSignature, 'BeLinksTxbelinksmodule') === false) {
            return array();
        }

        $parts = explode('BeLinksTxbelinksmodule', $moduleSignature);
        $uid = (int)array_pop($parts);
        if ($uid < 1) {
            return array();
        }

        $row = static::getDatabaseConnection()->exec_SELECTgetSingleRow(
            '*',
            'tx_belinks_link',
            'uid=' . $uid
        );
        if ($row === null) {
            return array();
        }

        return $row;
    }

    /**
     * @param array $moduleArray
     * @return array
     */
    public static function getDefaultModuleConfiguration($moduleArray)
    {
        $moduleSignature = static::getModuleSignature($moduleArray);
        $iconPathAndFilename = static::getModuleIcon($moduleArray['icon'], $moduleSignature);

        $moduleConfiguration = array(
            'access' => static::$authenticationArray[(int)$moduleArray['authentication']],
            'icon' => $iconPathAndFilename,
            'labels' => null,
        );

        return $moduleConfiguration;
    }

    /**
     * @param string $icon
     * @param string $moduleSignature
     * @return string
     */
    protected static function getModuleIcon($icon, $moduleSignature)
    {
        $defaultIcon = ExtensionManagementUtility::extPath('be_links') . 'ext_icon.gif';
        if (empty($icon)) {
            list($mainModule, $submodule) = explode('_', $moduleSignature, 2);
            if (empty($submodule) || empty($GLOBALS['TBE_MODULES']['_configuration'][$mainModule]['icon'])) {
                return $defaultIcon;
            }

            return $GLOBALS['TBE_MODULES']['_configuration'][$mainModule]['icon'];
        }

        $uploadFolder = $GLOBALS['TCA']['tx_belinks_link']['columns']['icon']['config']['uploadfolder'];
        $iconPathAndFilename = PATH_site . $uploadFolder . '/' . $icon;
        if (!file_exists($iconPathAndFilename)) {
            return $defaultIcon;
        }

        static::getGraphicalFunctions()->init();
        $tempPath = static::getGraphicalFunctions()->tempPath;
        if (!PathUtility::isAbsolutePath($tempPath)) {
            static::getGraphicalFunctions()->absPrefix = PATH_site;
        }
        $iconInformation = static::getGraphicalFunctions()->imageMagickConvert($iconPathAndFilename, null, null, null,
            null, null, array('maxH' => '18', 'maxW' => '18'));

        return $iconInformation === null ? $defaultIcon : $iconInformation[3];
    }

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected static function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }

    /**
     * @return \TYPO3\CMS\Core\Imaging\GraphicalFunctions
     */
    protected static function getGraphicalFunctions()
    {
        if (static::$graphicalFunctions === null) {
            static::$graphicalFunctions = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Imaging\\GraphicalFunctions');
        }

        return static::$graphicalFunctions;
    }
}
