<?php
namespace CPSIT\BeLinks\Tests\Functional\Hook;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Nicole Cordes <typo3@cordes.co>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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

use CPSIT\BeLinks\Hook\BootstrapHook;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Backend\Domain\Repository\Module\BackendModuleRepository;
use TYPO3\CMS\Backend\View\ModuleMenuView;
use TYPO3\CMS\Lang\LanguageService;

class BootstrapHookTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = array(
        'typo3conf/ext/be_links',
    );

    public function setUp()
    {
        parent::setUp();

        $languageService = new LanguageService();
        $languageService->init('default');
        $GLOBALS['LANG'] = $languageService;

        $fixturePath = ORIGINAL_ROOT . 'typo3conf/ext/be_links/Tests/Functional/Fixtures/';
        $this->importDataSet($fixturePath . 'tx_belinks_link.xml');
    }

    /**
     * @test
     */
    public function adminUserCanAccessAdminSubModule()
    {
        $expectedModuleData = array(
            'name' => 'help_BeLinksTxbelinksmodule1',
            'title' => 'Admin-only module',
        );

        if (version_compare(TYPO3_version, '8.0.0', '<')) {
            $expectedModule = array(
                'help_BeLinksTxbelinksmodule1_tab' => $expectedModuleData,
            );
        } else {
            $expectedModule = array(
                'help_BeLinksTxbelinksmodule1' => $expectedModuleData,
            );
        }

        $this->setUpBackendUserFromFixture(1);

        $bootstrap = new BootstrapHook();
        $bootstrap->processData();

        $modules = $this->getModuleData();

        $this->assertArraySubset($expectedModule, $modules['modmenu_help']['subitems']);
    }

    /**
     * @test
     */
    public function nonAdminUserCannotAccessAdminSubModule()
    {
        $backendUser = $this->setUpBackendUserFromFixture(1);
        $backendUser->user['admin'] = 0;

        $bootstrap = new BootstrapHook();
        $bootstrap->processData();

        $modules = $this->getModuleData();

        $this->assertArrayNotHasKey('subitems', $modules['modmenu_help']);
    }

    /**
     * @return array
     */
    protected function getModuleData()
    {
        if (method_exists('TYPO3\\CMS\\Backend\\Domain\\Repository\\Module\\BackendModuleRepository', 'getRawModuleMenuData')) {
            $repository = new BackendModuleRepository();

            return $repository->getRawModuleMenuData();
        }

        $repository = new ModuleMenuView();

        return $repository->getRawModuleData();
    }
}
