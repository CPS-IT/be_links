<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "be_links".
 *
 * Auto generated 12-11-2014 00:00
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Backend links',
	'description' => 'Add page or web links as backend modules',
	'category' => 'be',
	'state' => 'stable',
	'uploadfolder' => 1,
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'author' => 'Nicole Cordes',
	'author_email' => 'cordes@cps-it.de',
	'author_company' => 'CPS-IT GmbH',
	'version' => '0.1.1',
	'constraints' => 
	array (
		'depends' => 
		array (
			'php' => '5.3.7-0.0.0',
			'typo3' => '6.2.0-6.2.99',
		),
		'conflicts' => 
		array (
		),
		'suggests' => 
		array (
		),
	),
	'_md5_values_when_last_written' => 'a:14:{s:9:"ChangeLog";s:4:"f87d";s:20:"class.ext_update.php";s:4:"f737";s:12:"ext_icon.gif";s:4:"3405";s:17:"ext_localconf.php";s:4:"8f52";s:14:"ext_tables.sql";s:4:"463a";s:44:"Classes/Controller/BackendLinkController.php";s:4:"8f20";s:30:"Classes/Hook/BootstrapHook.php";s:4:"a796";s:23:"Classes/Module/conf.php";s:4:"0697";s:24:"Classes/Module/index.php";s:4:"39a7";s:33:"Classes/Utility/ModuleUtility.php";s:4:"8c8c";s:27:"Classes/View/ParentView.php";s:4:"ac7c";s:37:"Configuration/TCA/tx_belinks_link.php";s:4:"e121";s:43:"Resources/Private/Language/locallang_db.xlf";s:4:"d37f";s:42:"Resources/Public/Icons/tx_belinks_link.gif";s:4:"3405";}',
);

