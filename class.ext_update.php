<?php
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
        $databaseTables = $this->getDatabaseConnection()->admin_get_tables();
        foreach ($this->databaseTables as $table => $configuration) {
            if (!isset($databaseTables[$table])) {
                continue;
            }

            $rows = $this->getItemsFromDatabase($table, $configuration['fieldMap']);
            if (count((array)$rows) > 0) {
                return true;
            }
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
        $this->processUpdates();

        return $this->generateOutput();
    }

    /**
     * @return void
     */
    protected function processUpdates()
    {
        $databaseTables = $this->getDatabaseConnection()->admin_get_tables();
        foreach ($this->databaseTables as $table => $configuration) {
            if (!isset($databaseTables[$table])) {
                continue;
            }

            $fieldArray = $configuration['fieldMap'];
            $rows = $this->getItemsFromDatabase($table, $fieldArray);
            $this->messageArray[] = array(
                \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
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
                            \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
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
     * Generates output by using flash messages
     *
     * @return string
     */
    protected function generateOutput()
    {
        $output = '';
        foreach ($this->messageArray as $flashMessage) {
            /** @var \TYPO3\CMS\Core\Messaging\FlashMessage $flashMessage */
            $flashMessage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
                'TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                $flashMessage[2],
                $flashMessage[1],
                $flashMessage[0]);
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
