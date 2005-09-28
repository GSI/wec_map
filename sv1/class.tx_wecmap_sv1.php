<?php
/***************************************************************
* Copyright notice
*
* (c) 2005 Foundation for Evangelism (info@evangelize.org)
* All rights reserved
*
* This file is part of the Web-Empowered Church (WEC) ministry of the
* Foundation for Evangelism (http://evangelize.org). The WEC is developing 
* TYPO3-based free software for churches around the world. Our desire 
* use the Internet to help offer new life through Jesus Christ. Please
* see http://WebEmpoweredChurch.org/Jesus.
*
* You can redistribute this file and/or modify it under the terms of the 
* GNU General Public License as published by the Free Software Foundation; 
* either version 2 of the License, or (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This file is distributed in the hope that it will be useful for ministry,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the file!
***************************************************************/
/**
 * Service 'Geocoder.us Address Lookup' for the 'wec_map' extension.
 *
 * @author	Web-Empowered Church Team <map@webempoweredchurch.org>
 */


require_once(PATH_t3lib.'class.t3lib_svbase.php');

/**
 * Service providing lat/long lookup via the geocoder.us service.  
 *
 * @author Web Empowered Church Team <map@webempoweredchurch.org>
 * @package TYPO3
 * @subpackage tx_wecmap
 */
class tx_wecmap_sv1 extends t3lib_svbase {
	var $prefixId = 'tx_wecmap_sv1';		// Same as class name
	var $scriptRelPath = 'sv1/class.tx_wecmap_sv1.php';	// Path to this script relative to the extension dir.
	var $extKey = 'wec_map';	// The extension key.
	
	/**
	 * Performs an address lookup using the geocoder.us web service.
	 *
	 * @param	string	The street address.
	 * @param	string	The city name.
	 * @param	string	The state name.
	 * @param	string	The ZIP code.
	 * @return	array		Array containing latitude and longitude.  If lookup failed, empty array is returned.
	 */
	function lookup($street, $city, $state, $zip)	{
		
		$url = 'http://rpc.geocoder.us/service/rest?address=';
		$address = $street.', '.$city.', '.$state.' '.$zip;
		$address = str_replace(' ', '%20', $address);
		
		$xml = file_get_contents($url.$address);
		
		$latlong = array();
		if($xml != "couldn't find this address! sorry") {
			$xml = t3lib_div::xml2array($xml);
		
			$latlong['lat'] = $xml['geo:Point']['geo:lat'];
			$latlong['long'] = $xml['geo:Point']['geo:long'];
		}
		
		return $latlong;
	}
	
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_map/sv1/class.tx_wecmap_sv1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_map/sv1/class.tx_wecmap_sv1.php']);
}

?>