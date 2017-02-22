<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "be_links".
 *
 * Auto generated 22-02-2017 15:04
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
  'version' => '0.3.1',
  'constraints' => 
  array (
    'depends' => 
    array (
      'php' => '5.3.7-0.0.0',
      'typo3' => '6.2.0-7.6.99',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  '_md5_values_when_last_written' => 'a:24:{s:9:"ChangeLog";s:4:"081d";s:20:"class.ext_update.php";s:4:"537c";s:13:"composer.json";s:4:"c0bb";s:12:"ext_icon.gif";s:4:"3405";s:17:"ext_localconf.php";s:4:"7bd4";s:14:"ext_tables.sql";s:4:"78af";s:44:"Classes/Controller/BackendLinkController.php";s:4:"c66f";s:30:"Classes/Hook/BootstrapHook.php";s:4:"df18";s:23:"Classes/Module/conf.php";s:4:"4139";s:24:"Classes/Module/index.php";s:4:"3dd6";s:33:"Classes/Utility/ModuleUtility.php";s:4:"b283";s:27:"Classes/View/ParentView.php";s:4:"4910";s:37:"Configuration/TCA/tx_belinks_link.php";s:4:"b171";s:26:"Documentation/Includes.txt";s:4:"6d5f";s:23:"Documentation/Index.rst";s:4:"6317";s:23:"Documentation/Links.rst";s:4:"d18b";s:26:"Documentation/Settings.yml";s:4:"a406";s:46:"Documentation/Images/Introduction/ListView.png";s:4:"8879";s:48:"Documentation/Images/Introduction/ModuleView.png";s:4:"cc20";s:51:"Documentation/Images/UserManual/NewModuleRecord.png";s:4:"927a";s:36:"Documentation/Introduction/Index.rst";s:4:"be27";s:34:"Documentation/UserManual/Index.rst";s:4:"edbf";s:43:"Resources/Private/Language/locallang_db.xlf";s:4:"f2ac";s:42:"Resources/Public/Icons/tx_belinks_link.gif";s:4:"3405";}',
);

