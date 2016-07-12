<?php
namespace CPSIT\BeLinks\View;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;

class ParentView
{
    /**
     * @param array $parameters
     */
    public function addMainModuleItems(array $parameters)
    {
        $backendModules = $GLOBALS['TBE_MODULES'];
        // Unset all configuration items
        unset($backendModules['_PATHS']);
        unset($backendModules['_dispatcher']);
        unset($backendModules['_configuration']);
        unset($backendModules['_navigationComponents']);

        /** @var \TYPO3\CMS\Backend\Module\ModuleLoader $moduleLoader */
        $moduleLoader = GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Module\\ModuleLoader');
        $moduleLoader->load($backendModules);

        foreach ($backendModules as $moduleName => $submodules) {
            $moduleName = $moduleLoader->cleanName($moduleName);
            if (!empty($moduleName)) {
                $moduleLabel = $moduleName;
                if (!empty($GLOBALS['LANG']->moduleLabels['tabs'][$moduleName . '_tab'])) {
                    $moduleLabel = $GLOBALS['LANG']->moduleLabels['tabs'][$moduleName . '_tab'];
                }
                $parameters['items'][] = array(
                    $moduleLabel,
                    $moduleName,
                );
            }
        }
    }

}
