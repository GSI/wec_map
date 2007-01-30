<?php
/***************************************************************
* Copyright notice
*
* (c) 2007 Foundation For Evangelism (info@evangelize.org)
* All rights reserved
*
* This file is part of the Web-Empowered Church (WEC)
* (http://webempoweredchurch.org) ministry of the Foundation for Evangelism
* (http://evangelize.org). The WEC is developing TYPO3-based
* (http://typo3.org) free software for churches around the world. Our desire
* is to use the Internet to help offer new life through Jesus Christ. Please
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

require_once(t3lib_extMgm::extPath('wec_map').'class.tx_wecmap_map.php');
require_once(t3lib_extMgm::extPath('wec_map').'map_service/google/class.tx_wecmap_marker_google.php');
require_once(t3lib_extMgm::extPath('wec_map').'class.tx_wecmap_backend.php');

/**
 * Map implementation for the Google Maps mapping service.
 * 
 * @author Web-Empowered Church Team <map@webempoweredchurch.org>
 * @package TYPO3
 * @subpackage tx_wecmap
 */
class tx_wecmap_map_google extends tx_wecmap_map {
	var $lat;
	var $long;
	var $zoom;
	var $markers;
	var $width;
	var $height;
				
	var $js;
	var $key;
	var $controls;
	
	var $markerClassName = 'tx_wecmap_marker_google';
	
	/** 
	 * Class constructor.  Creates javscript array.
	 * @access	public
	 * @param	string		The Google Maps API Key
	 * @param	string		The latitude for the center point on the map.
	 * @param 	string		The longitude for the center point on the map.
	 * @param	string		The initial zoom level of the map.
	 */
	function tx_wecmap_map_google($key, $width=250, $height=250, $lat='', $long='', $zoom='') {
		$this->prefixId = "tx_wecmap_map_google";
		$this->js = array();
		$this->markers = array();
		
		if(!$key) {
			// get key from configuration
			$this->key = tx_wecmap_backend::getExtConf('apiKey.google');			
		} else {
			$this->key = $key;			
		}

		$this->controls = array();

		$this->width = $width;
		$this->height = $height;
		
		if ($lat != '' || $long != '') {
			$this->setCenter($lat, $long);
		}
		if ($zoom != '') {
			$this->setZoom($zoom);
		}
	}	
	
	/**
	 * Enables controls for Google Maps, for example zoom level slider or mini 
	 * map. Valid controls are largeMap, smallMap, scale, smallZoom, 
	 * overviewMap, and mapType.
	 *
	 * @access	public
	 * @param	string	The name of the control to add.
	 * @return	none
	 *
	 **/
	function addControl($name) {
		switch ($name)
		{
			case 'largeMap':
				$this->controls[] .= $this->js_addControl('map', "new GLargeMapControl()");
				break;
			
			case 'smallMap':
				$this->controls[] .= $this->js_addControl('map', "new GSmallMapControl()");
				break;
			
			case 'scale':
				$this->controls[] .= $this->js_addControl('map', "new GScaleControl()");
				break;
			
			case 'smallZoom':
				$this->controls[] .= $this->js_addControl('map', "new GSmallZoomControl()");
				break;

			case 'overviewMap':
				$this->controls[] .= $this->js_addControl('map', "new GOverviewMapControl()");
				break;
					
			case 'mapType':
				$this->controls[] .= $this->js_addControl('map', "new GMapTypeControl()");
				break;
			default:
				break;
		}
	}
	
	/**
	 * Main function to draw the map.  Outputs all the necessary HTML and
	 * Javascript to draw the map in the frontend or backend.
	 *
	 * @access	public
	 * @return	string	HTML and Javascript markup to draw the map.
	 */
	function drawMap() {		
		
		/* Initialize locallang.  If we're in the backend context, we're fine.
		   If we're in the frontend, then we need to manually set it up. */
		if(TYPO3_MODE == 'BE') {
			global $LANG;
		} else {
			require_once(PATH_typo3.'sysext/lang/lang.php');
			$LANG = t3lib_div::makeInstance('language');
			$LANG->init($GLOBALS['TSFE']->config['config']['language']);
		}
		$LANG->includeLLFile('EXT:wec_map/map_service/google/locallang.xml');
		
		$hasKey = $this->hasKey();
		$hasThingsToDisplay = $this->hasThingsToDisplay();
				
		// make sure we have markers to display and an API key
		if ($hasThingsToDisplay && $hasKey) { 						
			
			if(!isset($this->lat) or !isset($this->long)) {
				$this->autoCenterAndZoom();
			}
		
			/* If we're in the frontend, use TSFE.  Otherwise, include JS manually. */
			if(TYPO3_MODE == 'FE') {
				$GLOBALS["TSFE"]->JSeventFuncCalls["onload"][$this->prefixId]="drawMap();";	
				$GLOBALS["TSFE"]->JSeventFuncCalls["onunload"][$this->prefixId]="GUnload();";	
				$GLOBALS['TSFE']->additionalHeaderData[] = '<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.$this->key.'" type="text/javascript"></script>';
			} else {
				$htmlContent .= '<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.$this->key.'" type="text/javascript"></script>';
			}
		
			$htmlContent .= $this->mapDiv('map', $this->width, $this->height);
			$jsContent = array();
			$jsContent[] = $this->js_createMarker();
			$jsContent[] = $this->js_drawMapStart();
			$jsContent[] = $this->js_newGMap2('map');
			$jsContent[] = $this->js_setCenter('map', $this->lat, $this->long, $this->zoom);
			foreach( $this->controls as $control ) {
				$jsContent[] = $control;
			}
			$jsContent[] = $this->js_icon();
			$jsContent[] = $this->js_newGMarkerManager('mgr', 'map');
			$jsContent[] = 'var markers;';
			foreach($this->markers as $key => $markers) {
				$jsContent[] = 'markers = null;'; 
				$jsContent[] = 'markers = [];'; 
				$key = explode(':',$key);
				foreach( $markers as $marker ) {
					$jsContent[] = 'markers.push('. $marker->writeJS() .');';
				}
				$jsContent[] = 'mgr.addMarkers(markers, ' . $key[0] . ', ' . $key[1] . ');';
			}

			$jsContent[] = 'mgr.refresh();';
			$jsContent[] = $this->js_drawMapEnd();
		
			// there is no onload() in the BE, so we need to call drawMap() manually.
			if(TYPO3_MODE == 'FE') {
				$manualCall = null;
			} else {
				$manualCall = '<script type="text/javascript">setTimeout("drawMap()",1);</script>';
			}
		
			return $htmlContent.t3lib_div::wrapJS(implode(chr(10), $jsContent)).$manualCall;
		} else if (!$hasKey) {
			$error = '<span>'.$LANG->getLL('error_noApiKey').'</span>';
			return $error;
		} else if (!$hasThingsToDisplay) {
			$error = '<span>'.$LANG->getLL('error_nothingToDisplay').'</span>';
			return $error;
		}
	}
	
	
	/**
	 * Creates the overall map div.
	 * 
	 * @access	private
	 * @param	string		ID of the div tag.
	 * @param	integer		Width of the map in pixels.
	 * @param	integer		Height of the map in pixels.
	 * @return	string		The HTML for the map div tag.
	 */
	function mapDiv($id, $width, $height) {
		return '<div id="'.$id.'" style="width:'.$width.'px; height:'.$height.'px;"></div>';
	}
	
	/**
	 * Creates the marker creation function in Javascript.
	 * 
	 * @access	private
	 * @return	string		The Javascript code for the marker creation function.
	 */
	function js_createMarker() {
		return 'function createMarker(point, icon, text) {
					var marker = new GMarker(point, icon);
					if(text){
						GEvent.addListener(marker, "click", function() { marker.openInfoWindowHtml(text); });
					}
					return marker;
				}';
	}
	
	/**
	 * Creates the beginning of the drawMap function in Javascript.
	 *
	 * @access	private
	 * @return	string	The beginning of the drawMap function in Javascript.
	 */
	function js_drawMapStart() {
		return 'function drawMap() {						
					if (GBrowserIsCompatible()) {';
	}
	
	/**
	 * Creates the end of the drawMap function in Javascript.
	 *
	 * @access	private
	 * @return	string	The end of the drawMap function in Javascript.
	 */
	function js_drawMapEnd() {
		return '} }';
	}
	
	/**
	 * Creates the Google Maps Javascript object.
	 * @access	private
	 * @param	string		Name of the div that this map is attached to.
							Will also become the name of the map.
	 * @return	string		Javascript for the Google Maps object.
	 */
	function js_newGMap2($name) {
		return 'var '.$name.' = new GMap2(document.getElementById("'.$name.'"));';
	}	
	
	/**
	 * Creates the Marker Manager Javascript object.
	 *
	 * @access	private
	 * @param	string		Name of the marker manager.
	 * @param	string		Name of the map this marker manager applies to.
	 * @return	string		Javascript for the marker manager object.
	 */
	function js_newGMarkerManager($mgrName, $map) {
		return 'var ' . $mgrName . ' = new GMarkerManager(' . $map . ');';	
	}	
	
	/**
	 * Creates the map's center point in Javascript.
	 *
	 * @access	private
	 * @param	string		Name of the map to center.
	 * @param	float		Center latitude.
	 * @param	float		Center longitude.
	 * @param	integer		Initial zoom level.
	 * @return	string		Javascript to center and zoom the specified map.
	 */
	function js_setCenter($name, $lat, $long, $zoom) {
		return $name.'.setCenter(new GLatLng('.$lat.', '.$long.'), '.$zoom.');';
	}
	
	
	/**
	 * Creates Javascript to add map controls.
	 *
	 * @access	private
	 * @param	string		Name of the map.
	 * @param	string		Name of the control.
	 * @param	string		Javascript to add a control to the map.
	 */
	function js_addControl($name, $control) {
		return $name.'.addControl('.$control.');';
	}
	
	/**
	 * Creates Javascript to define marker icons.
	 * 
	 * @access	private
	 * @return	string		Javascript definitions for marker icons.
	 * @todo	Add support for custom icons.
	 */
	function js_icon() {
		/* If we're in the backend, get an absolute path.  Frontend can use a relative path. */
		if (TYPO3_MODE=='BE')	{
			$path = t3lib_div::getIndpEnv('TYPO3_SITE_URL').t3lib_extMgm::siteRelPath('wec_map');
		} else {
			$path = t3lib_extMgm::siteRelPath('wec_map');
		}

		return 'var icon = new GIcon();
				icon.image = "'.$path.'images/mm_20_red.png";
				icon.shadow = "'.$path.'images/mm_20_shadow.png";
				icon.iconSize = new GSize(12, 20);
				icon.shadowSize = new GSize(22, 20);
				icon.iconAnchor = new GPoint(6, 20);
				icon.infoWindowAnchor = new GPoint(5, 1);';
				
	}


	/**
	 * Sets the center and zoom values for the current map dynamically, based
	 * on the markers to be displayed on the map.
	 * 
	 * @access	private	
	 * @return	none
 	 */
	function autoCenterAndZoom() {	
		
		/* Get center and lat/long spans from parent object */
		$latLongData = $this->getLatLongData();
		
		$lat = $latLongData['lat']; /* Center latitude */
		$long = $latLongData['long']; /* Center longitude */
		$latSpan = $latLongData['latSpan']; /* Total latitude the map covers */
		$longSpan = $latLongData['longSpan']; /* Total longitude the map covers */
	
		//$pixelsPerLatDegree = pow(2, 17-$zoom);
		//$pixelsPerLongDegree = pow(2,17 - $zoom) *  0.77162458338772;
		$wZoom = log($this->width, 2) - log($longSpan, 2);
		$hZoom = log($this->height, 2) - log($latSpan, 2);
		
		/* Pick the lower of the zoom levels since we'd rather show too much */
		$zoom = floor(($wZoom < $hZoom) ? $wZoom : $hZoom);
		
		/* Don't zoom in too far if we only have a single marker.*/
		if ($zoom < 2) {
			$zoom = 2;
		}
		
		$this->setCenter($lat, $long);
		$this->setZoom($zoom);
	}
	
	/**
     * Checks if a map has markers or a 
     * specific center.Otherwise, we have nothing 
     * to draw.
     * @return        boolean        True/false whether the map is valid or not.
     */
    function hasThingsToDisplay() {
        $valid = false;
        
        if(sizeof($this->markers) > 0) {
            $validMarkers = true;
        }
        
        if(isset($this->lat) and isset($this->long)) {
            $validCenter = true;
        }
        
        /* If we have an API key along with markers or a center point, its valid */
        if($validMarkers or $validCenter) {
            $valid = true;
        }
        
        return $valid;
    }

	/**
	 * Checks if an API key has been entered and displays an error message instead of the map if not.
	 *
	 * @return boolean
	 **/
	function hasKey() {
		if($this->key) {
            return true;
        } else {
			return false;
		}
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_map/map_service/google/class.tx_wecmap_map_google.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_map/map_service/google/class.tx_wecmap_map_google.php']);
}


?>
