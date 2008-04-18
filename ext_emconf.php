<?php

########################################################################
# Extension Manager/Repository config file for ext: "wec_map"
#
# Auto generated 11-04-2008 16:06
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'WEC Map',
	'description' => 'Mapping extension that connects to geocoding databases and Google Maps API.',
	'category' => 'plugin',
	'shy' => 1,
	'dependencies' => '',
	'conflicts' => '',
	'priority' => 'bottom',
	'loadOrder' => '',
	'module' => 'mod1,mod2',
	'state' => 'stable',
	'internal' => 0,
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author' => 'Web-Empowered Church Team',
	'author_email' => 'map@webempoweredchurch.org',
	'author_company' => 'Foundation For Evangelism',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '2.0.1',
	'_md5_values_when_last_written' => 'a:69:{s:9:"CHANGELOG";s:4:"ee81";s:27:"class.tx_wecmap_backend.php";s:4:"7697";s:32:"class.tx_wecmap_batchgeocode.php";s:4:"e138";s:25:"class.tx_wecmap_cache.php";s:4:"b088";s:29:"class.tx_wecmap_domainmgr.php";s:4:"66ca";s:23:"class.tx_wecmap_map.php";s:4:"1c2d";s:26:"class.tx_wecmap_marker.php";s:4:"de23";s:31:"class.tx_wecmap_markergroup.php";s:4:"425e";s:26:"class.tx_wecmap_shared.php";s:4:"3975";s:21:"ext_conf_template.txt";s:4:"15b6";s:12:"ext_icon.gif";s:4:"9d48";s:17:"ext_localconf.php";s:4:"ac4b";s:14:"ext_tables.php";s:4:"60ba";s:14:"ext_tables.sql";s:4:"9080";s:16:"locallang_db.xml";s:4:"0d0b";s:7:"tca.php";s:4:"6117";s:29:"contrib/tablesort/fastinit.js";s:4:"afbd";s:30:"contrib/tablesort/tablesort.js";s:4:"c6e0";s:24:"csh/locallang_csh_ff.xml";s:4:"c0b3";s:14:"doc/manual.sxw";s:4:"24a7";s:52:"geocode_service/class.tx_wecmap_geocode_geocoder.php";s:4:"e6c7";s:50:"geocode_service/class.tx_wecmap_geocode_google.php";s:4:"c8ba";s:52:"geocode_service/class.tx_wecmap_geocode_worldkit.php";s:4:"a3a3";s:49:"geocode_service/class.tx_wecmap_geocode_yahoo.php";s:4:"eb08";s:14:"images/aai.gif";s:4:"03ce";s:20:"images/icon_home.gif";s:4:"6e80";s:27:"images/icon_home_shadow.png";s:4:"ce1c";s:20:"images/mm_20_red.png";s:4:"453d";s:23:"images/mm_20_shadow.png";s:4:"f77b";s:49:"map_service/google/class.tx_wecmap_map_google.php";s:4:"099e";s:52:"map_service/google/class.tx_wecmap_marker_google.php";s:4:"c9bb";s:32:"map_service/google/locallang.xml";s:4:"ddde";s:47:"map_service/yahoo/class.tx_wecmap_map_yahoo.php";s:4:"78a3";s:28:"map_service/yahoo/yahoo.tmpl";s:4:"a46c";s:42:"mod1/class.tx_wecmap_batchgeocode_util.php";s:4:"b8e3";s:38:"mod1/class.tx_wecmap_recordhandler.php";s:4:"acb1";s:14:"mod1/clear.gif";s:4:"cc11";s:13:"mod1/conf.php";s:4:"3a85";s:14:"mod1/index.php";s:4:"982a";s:18:"mod1/locallang.xml";s:4:"3a8c";s:22:"mod1/locallang_mod.xml";s:4:"5106";s:19:"mod1/moduleicon.gif";s:4:"7479";s:34:"mod1/tx_wecmap_batchgeocode_ai.php";s:4:"1da2";s:35:"mod1/tx_wecmap_recordhandler_ai.php";s:4:"2640";s:14:"mod2/clear.gif";s:4:"cc11";s:13:"mod2/conf.php";s:4:"5c71";s:14:"mod2/index.php";s:4:"b0db";s:18:"mod2/locallang.xml";s:4:"4a3f";s:22:"mod2/locallang_mod.xml";s:4:"341f";s:19:"mod2/moduleicon.gif";s:4:"6bde";s:14:"pi1/ce_wiz.gif";s:4:"6401";s:27:"pi1/class.tx_wecmap_pi1.php";s:4:"8fb7";s:35:"pi1/class.tx_wecmap_pi1_wizicon.php";s:4:"dc20";s:19:"pi1/flexform_ds.xml";s:4:"bc8f";s:17:"pi1/locallang.xml";s:4:"ae42";s:20:"pi1/static/setup.txt";s:4:"4ecd";s:14:"pi2/ce_wiz.gif";s:4:"56e0";s:27:"pi2/class.tx_wecmap_pi2.php";s:4:"eb5e";s:35:"pi2/class.tx_wecmap_pi2_wizicon.php";s:4:"f426";s:19:"pi2/flexform_ds.xml";s:4:"520f";s:17:"pi2/locallang.xml";s:4:"69e4";s:20:"pi2/static/setup.txt";s:4:"c915";s:14:"pi3/ce_wiz.gif";s:4:"6401";s:27:"pi3/class.tx_wecmap_pi3.php";s:4:"a04f";s:35:"pi3/class.tx_wecmap_pi3_wizicon.php";s:4:"2b04";s:19:"pi3/flexform_ds.xml";s:4:"396b";s:17:"pi3/locallang.xml";s:4:"1852";s:20:"pi3/static/setup.txt";s:4:"1ac0";s:16:"static/setup.txt";s:4:"0f61";}',
	'constraints' => array(
		'depends' => array(
			'php' => '3.0.0-0.0.0',
			'typo3' => '4.1.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
);

?>