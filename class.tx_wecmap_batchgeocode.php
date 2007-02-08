<?php

require_once(t3lib_extMgm::extPath('wec_map').'class.tx_wecmap_cache.php');

class tx_wecmap_batchgeocode {
	
	var $tables;
	var $geocodedAddresses;
	var $geocodeLimit;
	var $processedAddresses;
	
	function tx_wecmap_batchgeocode() {
		$this->tables = array();	
		$this->geocodedAddresses = 0;
		$this->processedAddresses = 0;
		$this->geocodeLimit = 10;
	}
	
	function addTable($table) {
		$this->tables[] = $table;
	}
	
	function addAllTables() {
		global $TCA, $LANG;
		
		foreach($TCA as $tableName => $tableContents) {
			if($tableContents['ctrl']['EXT']['wec_map']['isMappable']) {
				$this->tables[] = $tableName;
			}
		}
	}
	
	function geocode() {
		foreach($this->tables as $table) {		
			if($this->stopGeocoding()) {
				return;
			} else {			
				$this->geocodeTable($table);
			}
		}
	}
	
	function geocodeTable($table) {
		global $TCA, $TYPO3_DB;
		
		$addressFields = $TCA[$table]['ctrl']['EXT']['wec_map']['addressFields'];
		
		$result = $TYPO3_DB->exec_SELECTquery('*', $table, "");
		while($row = $TYPO3_DB->sql_fetch_assoc($result)) {
			
			if($this->stopGeocoding()) {
				return;
			} else {
				$this->geocodeRecord($row, $addressFields);
			}
		}		
	}
	
	function geocodeRecord($row, $addressFields) {
		$street = $row[$addressFields['street']];
		$city = $row[$addressFields['city']];
		$state = $row[$addressFields['state']];
		$zip = $row[$addressFields['zip']];
		$country = $row[$addressFields['country']];
		
		// increment total count
		$this->processedAddresses++;
		
		tx_wecmap_cache::lookupWithCallback($street, $city, $state, $zip, $country, '', false, $this);		
	}
	
	function callback_lookupThroughGeocodeService() {
		$this->geocodedAddresses++;
	}
	
	function stopGeocoding() {		
		if($this->geocodedAddresses >= $this->geocodeLimit) {
			return true;
		} else {
			return false;
		}
	}
	
	function processedAddresses() {
		return $this->processedAddresses;
	}
	
	function geocodedAddresses() {
		return $this->geocodedAddresses;
	}
	
	function recordCount() {
		global $TYPO3_DB;
		
		$recordCount = 0;
		
		foreach($this->tables as $table) {		
			$result = $TYPO3_DB->exec_SELECTquery('COUNT(*)', $table, "");
			$row = $TYPO3_DB->sql_fetch_assoc($result);
			$recordCount += $row['COUNT(*)'];
		}
		
		return $recordCount;
	}
}

?>