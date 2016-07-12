<?php
namespace CPSIT\BeLinks\Controller;

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

use TYPO3\CMS\Backend\Module\BaseScriptClass;

/**
 * Shows an iframe with a configured url
 *
 * @author Nicole Cordes <cordes@cps-it.de>
 * @package TYPO3
 * @subpackage tx_belinks
 */
class BackendLinkController extends BaseScriptClass
{
    public function __construct()
    {
        $GLOBALS['BE_USER']->modAccess($GLOBALS['MCONF'], true);
    }

    /**
     * @return void
     */
    public function main()
    {
        if (!empty($GLOBALS['moduleArray']['url'])) {
            $this->content = '<iframe src="' . $GLOBALS['moduleArray']['url'] . '" width="100%" height="100%" id="tx_belinks_iframe" frameborder="0" border="0"></iframe>';
        }
    }

    /**
     * @return void
     */
    public function printContent()
    {
        echo $this->content;
    }
}
