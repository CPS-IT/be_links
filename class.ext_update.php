<?php
namespace CPSIT\BeLinks;

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

use CPSIT\BeLinks\Utility\ModuleUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Update class for the extension manager.
 *
 */
class ext_update
{
    /**
     * @var array
     */
    protected $messageArray = array();

    /**
     * @var array
     */
    protected $databaseTables = array(
        'tx_lzlinks_links' => array(
            'uploadFolder' => 'uploads/tx_lzlinks/',
            'fieldMap' => array(
                'pid' => 'pid',
                'cruser_id' => 'cruser_id',
                'crdate' => 'crdate',
                'tstamp' => 'tstamp',
                'deleted' => 'deleted',
                'hidden' => 'hidden',
                'title' => 'title',
                'type' => 'type',
                'url' => 'url',
                'icon' => 'image',
                'authentication' => 'auth',
                'parent' => 'parent',
            ),
        ),
    );

    /**
     * Called by the extension manager to determine if the update menu entry
     * should be shown
     *
     * @return bool
     */
    public function access()
    {
        $databaseConnection = $this->getDatabaseConnection();
        $databaseTables = $databaseConnection->admin_get_tables();
        $similarities = array_intersect_key($this->databaseTables, $databaseTables);
        foreach ($similarities as $table => $configuration) {
            $rows = $this->getItemsFromDatabase($table, $configuration['fieldMap']);
            if (count((array)$rows) > 0) {
                return true;
            }
        }

        $count = $databaseConnection->exec_SELECTcountRows(
            '*',
            'tx_belinks_link',
            'parent LIKE ' . $databaseConnection->fullQuoteStr(
                $databaseConnection->escapeStrForLike('TxBeLinksModule', 'tx_belinks_link') . '%',
                'tx_belinks_link'
            ) . ' AND deleted=0'
        );
        if ($count > 0) {
            return true;
        }

        $count = $this->getDatabaseConnection()->exec_SELECTcountRows(
            '*',
            'be_groups',
            '(groupMods LIKE ' . $databaseConnection->fullQuoteStr(
                '%' . $databaseConnection->escapeStrForLike('_TxBeLinksModule', 'be_groups') . '%',
                'be_groups'
            ) . ' OR groupMods LIKE ' . $databaseConnection->fullQuoteStr(
                $databaseConnection->escapeStrForLike('TxBeLinksModule', 'be_groups') . '%',
                'be_groups'
            ) . ' OR groupMods LIKE ' . $databaseConnection->fullQuoteStr(
                '%,' . $databaseConnection->escapeStrForLike('TxBeLinksModule', 'be_groups') . '%',
                'be_groups'
            ) . ')' . ' AND deleted=0'
        );
        if ($count > 0) {
            return true;
        }

        $count = $this->getDatabaseConnection()->exec_SELECTcountRows(
            '*',
            'be_users',
            '(userMods LIKE ' . $databaseConnection->fullQuoteStr(
                '%' . $databaseConnection->escapeStrForLike('_TxBeLinksModule', 'be_users') . '%',
                'be_users'
            ) . ' OR userMods LIKE ' . $databaseConnection->fullQuoteStr(
                $databaseConnection->escapeStrForLike('TxBeLinksModule', 'be_users') . '%',
                'be_users'
            ) . ' OR userMods LIKE ' . $databaseConnection->fullQuoteStr(
                '%,' . $databaseConnection->escapeStrForLike('TxBeLinksModule', 'be_users') . '%',
                'be_users'
            ) . ')' . ' AND deleted=0'
        );
        if ($count > 0) {
            return true;
        }

        return false;
    }

    /**
     * Main update function called by the extension manager
     *
     * @return string
     */
    public function main()
    {
        $this->migrateDatabases();
        $this->migrateBackendModuleName();

        return $this->generateOutput();
    }

    /**
     * @return void
     */
    protected function migrateDatabases()
    {
        $databaseTables = $this->getDatabaseConnection()->admin_get_tables();
        $similarities = array_intersect_key($this->databaseTables, $databaseTables);
        foreach ($similarities as $table => $configuration) {
            $fieldArray = $configuration['fieldMap'];
            $rows = $this->getItemsFromDatabase($table, $fieldArray);
            $this->messageArray[] = array(
                FlashMessage::OK,
                'Table: ' . $table,
                count($rows) . (count($rows) > 1 ? ' items were' : ' item was') . ' migrated from database table "' . $table . '"',
            );
            foreach ((array)$rows as $row) {
                $insertArray = array();
                foreach ($fieldArray as $key => $field) {
                    if (!empty($field)) {
                        $insertArray[$key] = $row[$field];
                    }
                }
                unset($key, $field);
                $this->getDatabaseConnection()->exec_INSERTquery('tx_belinks_link', $insertArray);

                if (!empty($row[$fieldArray['icon']])) {
                    if (!file_exists(PATH_site . 'uploads/tx_belinks/' . $row[$fieldArray['icon']])) {
                        copy(
                            PATH_site . $configuration['uploadFolder'] . $row[$fieldArray['icon']],
                            PATH_site . 'uploads/tx_belinks/' . $row[$fieldArray['icon']]
                        );
                    } else {
                        $this->messageArray[] = array(
                            FlashMessage::ERROR,
                            'Icon could not be saved',
                            'The file "' . $row[$fieldArray['icon']] . '" already exists in upload folder. Please check any change yourself.<br />' .
                            '(Table: ' . $table . ' / Item: ' . $row['uid'] . ')',
                        );
                    }
                }
            }
            unset($row);
        }
    }

    /**
     * @return void
     */
    protected function migrateBackendModuleName()
    {
        $this->migrateParentModuleName();
        $this->migrateBackendGroupModules();
        $this->migrateBackendUserModules();
    }

    /**
     * @return void
     */
    protected function migrateParentModuleName()
    {
        $result = $this->getDatabaseConnection()->exec_SELECTquery(
            '*',
            'tx_belinks_link',
            'parent LIKE ' . $this->getDatabaseConnection()->fullQuoteStr('TxBeLinksModule%', 'tx_belinks_link')
            . ' AND deleted=0'
        );
        $count = $this->getDatabaseConnection()->sql_num_rows($result);
        if ($count > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->getDatabaseConnection()->exec_UPDATEquery(
                    'tx_belinks_link',
                    'uid=' . $row['uid'],
                    array(
                        'parent' => $this->migrateToNewModuleSignature($row['parent']),
                    )
                );
            }
            $this->messageArray[] = array(
                FlashMessage::OK,
                'Module configuration updated',
                $count . ($count > 1 ? ' configurations were' : ' configuration was') . ' updated to new module name',
            );
            $this->getDatabaseConnection()->sql_free_result($result);
        }
    }

    protected function migrateBackendGroupModules()
    {
        $result = $this->getDatabaseConnection()->exec_SELECTquery(
            '*',
            'be_groups',
            '(groupMods LIKE ' . $this->getDatabaseConnection()->fullQuoteStr('%\_TxBeLinksModule%', 'tx_belinks_link')
            . ' OR groupMods LIKE ' . $this->getDatabaseConnection()->fullQuoteStr('TxBeLinksModule%', 'tx_belinks_link')
            . ' OR groupMods LIKE ' . $this->getDatabaseConnection()->fullQuoteStr('%,TxBeLinksModule%', 'tx_belinks_link')
            . ')' . ' AND deleted=0'
        );
        $count = $this->getDatabaseConnection()->sql_num_rows($result);
        if ($count > 0) {
            while ($row = $result->fetch_assoc()) {
                $modules = GeneralUtility::trimExplode(',', $row['groupMods']);
                foreach ($modules as &$module) {
                    list($main, $sub) = explode('_', $module);
                    if (strpos($main, 'TxBeLinksModule') !== false) {
                        $main = $this->migrateToNewModuleSignature($main);
                    }
                    if (strpos($sub, 'TxBeLinksModule') !== false) {
                        $sub = $this->migrateToNewModuleSignature($sub);
                    }
                    $module = implode('_', array_filter(array($main, $sub)));
                }
                $this->getDatabaseConnection()->exec_UPDATEquery(
                    'be_groups',
                    'uid=' . $row['uid'],
                    array(
                        'groupMods' => implode(',', $modules),
                    )
                );
            }
            $this->messageArray[] = array(
                FlashMessage::OK,
                'Backend usergroup updated',
                $count . ($count > 1 ? ' configurations were' : ' configuration was') . ' updated to new module name',
            );
            $this->getDatabaseConnection()->sql_free_result($result);
        }
    }

    protected function migrateBackendUserModules()
    {
        $result = $this->getDatabaseConnection()->exec_SELECTquery(
            '*',
            'be_users',
            '(userMods LIKE ' . $this->getDatabaseConnection()->fullQuoteStr('%\_TxBeLinksModule%', 'tx_belinks_link')
            . ' OR userMods LIKE ' . $this->getDatabaseConnection()->fullQuoteStr('TxBeLinksModule%', 'tx_belinks_link')
            . ' OR userMods LIKE ' . $this->getDatabaseConnection()->fullQuoteStr('%,TxBeLinksModule%', 'tx_belinks_link')
            . ')' . ' AND deleted=0'
        );
        $count = $this->getDatabaseConnection()->sql_num_rows($result);
        if ($count > 0) {
            while ($row = $result->fetch_assoc()) {
                $modules = GeneralUtility::trimExplode(',', $row['userMods']);
                foreach ($modules as &$module) {
                    list($main, $sub) = explode('_', $module);
                    if (strpos($main, 'TxBeLinksModule') !== false) {
                        $main = $this->migrateToNewModuleSignature($main);
                    }
                    if (strpos($sub, 'TxBeLinksModule') !== false) {
                        $sub = $this->migrateToNewModuleSignature($sub);
                    }
                    $module = implode('_', array_filter(array($main, $sub)));
                }
                $this->getDatabaseConnection()->exec_UPDATEquery(
                    'be_users',
                    'uid=' . $row['uid'],
                    array(
                        'userMods' => implode(',', $modules),
                    )
                );
            }
            $this->messageArray[] = array(
                FlashMessage::OK,
                'Backend user updated',
                $count . ($count > 1 ? ' configurations were' : ' configuration was') . ' updated to new module name',
            );
            $this->getDatabaseConnection()->sql_free_result($result);
        }
    }

    /**
     * @param string $oldModuleSignature
     * @return string
     */
    protected function migrateToNewModuleSignature($oldModuleSignature)
    {
        $parts = explode('TxBeLinksModule', $oldModuleSignature);
        $uid = (int)array_pop($parts);

        return ModuleUtility::getModuleSignature(array('uid' => $uid));
    }

    /**
     * Generates output by using flash messages
     *
     * @return string
     */
    protected function generateOutput()
    {
        $output = '';
        foreach ($this->messageArray as $flashMessage) {
            /** @var FlashMessage $flashMessage */
            $flashMessage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
                'TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                $flashMessage[2],
                $flashMessage[1],
                $flashMessage[0]
            );
            $output .= $flashMessage->render();
        }

        return $output;
    }

    /**
     * @param string $table
     * @param array $fieldArray
     * @return array
     */
    protected function getItemsFromDatabase($table, $fieldArray)
    {
        return $this->getDatabaseConnection()->exec_SELECTgetRows(
            $table . '.*',
            $table . ' LEFT JOIN tx_belinks_link ON' .
            ' tx_belinks_link.title=' . $table . '.' . $fieldArray['title'] . ' AND tx_belinks_link.url=' . $table . '.' . $fieldArray['url'],
            'ISNULL(tx_belinks_link.uid)'
        );
    }

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
