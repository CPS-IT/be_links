<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "be_links".
 *
 * Auto generated 13-01-2018 20:59
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
  'version' => '1.0.0',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '6.2.0-8.7.99',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  '_md5_values_when_last_written' => 'a:26:{s:9:"ChangeLog";s:4:"4e17";s:9:"README.md";s:4:"4f52";s:20:"class.ext_update.php";s:4:"e986";s:13:"composer.json";s:4:"ce21";s:13:"composer.lock";s:4:"1f3b";s:12:"ext_icon.gif";s:4:"3405";s:17:"ext_localconf.php";s:4:"7bd4";s:14:"ext_tables.sql";s:4:"78af";s:44:"Classes/Controller/BackendLinkController.php";s:4:"f768";s:30:"Classes/Hook/BootstrapHook.php";s:4:"f0ec";s:33:"Classes/Utility/ModuleUtility.php";s:4:"dbd8";s:27:"Classes/View/ParentView.php";s:4:"4910";s:37:"Configuration/TCA/tx_belinks_link.php";s:4:"b171";s:26:"Documentation/Includes.txt";s:4:"6d5f";s:23:"Documentation/Index.rst";s:4:"6317";s:23:"Documentation/Links.rst";s:4:"d18b";s:26:"Documentation/Settings.yml";s:4:"a406";s:46:"Documentation/Images/Introduction/ListView.png";s:4:"8879";s:48:"Documentation/Images/Introduction/ModuleView.png";s:4:"cc20";s:51:"Documentation/Images/UserManual/NewModuleRecord.png";s:4:"927a";s:36:"Documentation/Introduction/Index.rst";s:4:"be27";s:34:"Documentation/UserManual/Index.rst";s:4:"edbf";s:43:"Resources/Private/Language/locallang_db.xlf";s:4:"f2ac";s:42:"Resources/Public/Icons/tx_belinks_link.gif";s:4:"3405";s:45:"Tests/Functional/Fixtures/tx_belinks_link.xml";s:4:"c7ba";s:43:"Tests/Functional/Hook/BootstrapHookTest.php";s:4:"ee7d";}',
);

