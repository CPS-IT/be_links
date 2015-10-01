<?php
namespace CPSIT\BeLinks\Hook;

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

class BootstrapHook implements \TYPO3\CMS\Core\Database\TableConfigurationPostProcessingHookInterface {

	/**
	 * @return void
	 */
	public function processData() {
		$this->addMainModules();
		$this->addSubmodules();
	}

	/**
	 * @return void
	 */
	protected function addMainModules() {
		$rowArray = static::getDatabaseConnection()->exec_SELECTgetRows(
			'*',
			'tx_belinks_link',
			'hidden=0 AND deleted=0 AND type=1'
		);
		if (!empty($rowArray)) {
			foreach ($rowArray as $row) {
				$this->addModule(
						$row,
						'TxBeLinksModule' . $row['uid']
				);
			}
		}
	}

	/**
	 * @return void
	 */
	protected function addSubmodules() {
		$rowArray = static::getDatabaseConnection()->exec_SELECTgetRows(
			'*',
			'tx_belinks_link',
			'hidden=0 AND deleted=0 AND type=0'
		);
		if (!empty($rowArray)) {
			foreach ($rowArray as $row) {
				$this->addModule(
					$row,
					$row['parent'],
					'TxBeLinksModule' . $row['uid']
				);
			}
		}
	}

	/**
	 * @param array $row
	 * @param string $parentModule
	 * @param string $submodule
	 * @param string $position
	 * @return void
	 */
	protected function addModule($row, $parentModule, $submodule = '', $position = '') {
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
			$parentModule,
			$submodule,
			$position,
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('be_links') . 'Classes/Module/',
			array_merge(
				\CPSIT\BeLinks\Utility\ModuleUtility::getDefaultModuleConfiguration($row),
				array(
					'configureModuleFunction' => array(
						'CPSIT\\BeLinks\\Hook\\BootstrapHook',
						'addModuleConfiguration'
					),
				)
			)
		);
	}

	/**
	 * @param string $moduleSignature
	 * @return array
	 */
	public function addModuleConfiguration($moduleSignature) {
		$moduleArray = \CPSIT\BeLinks\Utility\ModuleUtility::getModuleArray($moduleSignature);
		if (empty($moduleArray)) {
			return array();
		}

		$moduleConfiguration = $GLOBALS['TBE_MODULES']['_configuration'][$moduleSignature];
		static::getLanguageService()->addModuleLabels(
			array(
				'tabs_images' => array(
					'tab' => $moduleConfiguration['icon'],
				),
				'labels' => array(
					'tablabel' => $moduleArray['title'],
					'tabdescr' => $moduleArray['title'],
				),
				'tabs' => array(
					'tab' => $moduleArray['title'],
				)
			),
			$moduleSignature . '_'
		);

		$moduleConfiguration['script'] = 'dummy.php';
		if (strpos($moduleSignature, '_') !== FALSE) {
			$moduleConfiguration['script'] = \TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl($moduleSignature);
		}

		return $moduleConfiguration;
	}

	/**
	 * @return \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	static protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}

	/**
	 * @return \TYPO3\CMS\Lang\LanguageService
	 */
	static protected function getLanguageService() {
		return $GLOBALS['LANG'];
	}

}

?>