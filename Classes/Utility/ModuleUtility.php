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

final class ModuleUtility {

	/**
	 * @var array
	 */
	static protected $authenticationArray = array(
		0 => 'user,group',
		1 => 'admin',
		2 => 'user',
		3 => 'group',
	);

	/**
	 * @var \TYPO3\CMS\Core\Imaging\GraphicalFunctions
	 */
	static protected $graphicalFunctions = NULL;

	/**
	 * @param array $moduleArray
	 * @return string
	 */
	static public function getModuleSignature($moduleArray) {
		$moduleSignature = 'TxBeLinksModule' . $moduleArray['uid'];
		if (!empty($moduleArray['parent'])) {
			$moduleSignature = $moduleArray['parent'] . '_' . $moduleSignature;
		}

		return $moduleSignature;
	}

	/**
	 * @param string $moduleSignature
	 * @return array
	 */
	static public function getModuleArray($moduleSignature) {
		if (strpos($moduleSignature, 'TxBeLinksModule') === FALSE) {
			return array();
		}

		$parts = explode('TxBeLinksModule', $moduleSignature);
		$uid = (int) array_pop($parts);
		if ($uid < 1) {
			return array();
		}

		$row = static::getDatabaseConnection()->exec_SELECTgetSingleRow(
			'*',
			'tx_belinks_link',
			'uid=' . $uid
		);
		if ($row === NULL) {
			return array();
		}

		return $row;
	}

	/**
	 * @param array $moduleArray
	 * @return array
	 */
	static public function getDefaultModuleConfiguration($moduleArray) {
		$moduleSignature = static::getModuleSignature($moduleArray);
		$iconPathAndFilename = static::getModuleIcon($moduleArray['icon']);

		$moduleConfiguration = array(
			'name' => $moduleSignature,
			'access' => static::$authenticationArray[(int) $moduleArray['authentication']],
			'icon' => $iconPathAndFilename,
		);

		return $moduleConfiguration;
	}

	/**
	 * @param string $icon
	 * @return string
	 */
	static protected function getModuleIcon($icon) {
		$defaultIcon = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('be_links') . 'ext_icon.gif';
		if (empty($icon)) {
			return $defaultIcon;
		}

		$uploadFolder = $GLOBALS['TCA']['tx_belinks_link']['columns']['icon']['config']['uploadfolder'];
		$iconPathAndFilename = PATH_site . $uploadFolder . '/' . $icon;
		if (!file_exists($iconPathAndFilename)) {
			return $defaultIcon;
		}

		static::getGraphicalFunctions()->init();
		static::getGraphicalFunctions()->tempPath = PATH_site . static::getGraphicalFunctions()->tempPath;
		$iconInformation = static::getGraphicalFunctions()->imageMagickConvert($iconPathAndFilename, NULL, NULL, NULL, NULL, NULL, array('maxH' => '18', 'maxW' => '18'));

		return $iconInformation[3];
	}

	/**
	 * @return \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	static protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}

	/**
	 * @return \TYPO3\CMS\Core\Imaging\GraphicalFunctions
	 */
	static protected function getGraphicalFunctions() {
		if (static::$graphicalFunctions === NULL) {
			static::$graphicalFunctions = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Imaging\\GraphicalFunctions');
		}

		return static::$graphicalFunctions;
	}

}

?>