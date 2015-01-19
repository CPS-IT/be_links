<?php

return array(
	'ctrl' => array(
		'title' => 'LLL:EXT:be_links/Resources/Private/Language/locallang_db.xlf:tx_belinks_link',
		'label' => 'title',
		'cruser_id' => 'cruser_id',
		'crdate' => 'crdate',
		'tstamp' => 'tstamp',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),

		'type' => 'type',
		'default_sortby' => 'ORDER BY crdate ASC',

		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('be_links') . 'Resources/Public/Icons/tx_belinks_link.gif',
	),
	'interface' => array(
		'showRecordFieldList' => 'hidden, title, type, url, icon, auth, parent',
	),
	'columns' => array(
		'hidden' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0',
			),
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:be_links/Resources/Private/Language/locallang_db.xlf:tx_belinks_link.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required,trim',
			),
		),
		'type' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:be_links/Resources/Private/Language/locallang_db.xlf:tx_belinks_link.type',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array(
						'LLL:EXT:be_links/Resources/Private/Language/locallang_db.xlf:tx_belinks_link.type.I.1',
						'0'
					),
					array(
						'LLL:EXT:be_links/Resources/Private/Language/locallang_db.xlf:tx_belinks_link.type.I.2',
						'1'
					),
				),
			),
		),
		'url' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:be_links/Resources/Private/Language/locallang_db.xlf:tx_belinks_link.url',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			),
		),
		'icon' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:be_links/Resources/Private/Language/locallang_db.xlf:tx_belinks_link.icon',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => 'gif,png,jpeg,jpg',
				'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
				'uploadfolder' => 'uploads/tx_belinks',
				'show_thumbs' => 1,
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'authentication' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:be_links/Resources/Private/Language/locallang_db.xlf:tx_belinks_link.authentication',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array(
						'LLL:EXT:be_links/Resources/Private/Language/locallang_db.xlf:tx_belinks_link.authentication.I.0',
						0
					),
					array(
						'LLL:EXT:be_links/Resources/Private/Language/locallang_db.xlf:tx_belinks_link.authentication.I.1',
						1
					),
					array(
						'LLL:EXT:be_links/Resources/Private/Language/locallang_db.xlf:tx_belinks_link.authentication.I.2',
						2
					),
					array(
						'LLL:EXT:be_links/Resources/Private/Language/locallang_db.xlf:tx_belinks_link.authentication.I.3',
						3
					),
					array(
						'LLL:EXT:be_links/Resources/Private/Language/locallang_db.xlf:tx_belinks_link.authentication.I.4',
						4
					),
				),
			),
		),
		'parent' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:be_links/Resources/Private/Language/locallang_db.xlf:tx_belinks_link.parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array(
						'LLL:EXT:be_links/Resources/Private/Language/locallang_db.xlf:tx_belinks_link.parent.I.0',
						'0'
					),
				),
				'itemsProcFunc' => 'CPSIT\\BeLinks\\View\\ParentView->addMainModuleItems',
			),
		),
	),
	'types' => array(
		'0' => array(
			'showitem' => 'hidden;;1;;1-1-1, title;;;;2-2-2, type;;;;3-3-3, url, icon, authentication, parent'
		),
		'1' => array(
			'showitem' => 'hidden;;1;;1-1-1, title;;;;2-2-2, type;;;;3-3-3, url, icon, authentication'
		),
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
);

?>