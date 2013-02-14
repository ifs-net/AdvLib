<?php

 /**
  * initialise the module
  *
  */
function ifs_init() {
  	// Create tables
  	$tables = array (
  		'ifs_cache',
  		'ifs_cache_content'
		  );
	foreach ($tables as $table) {
		if (!DBUtil::createTable($table)) {
		  	return false;
		}
	}
	// Create indexes
    if (!DBUtil::createIndex('cacheindex', 'ifs_cache', array('mid'))) {
        return LogUtil::registerError(_CREATEINDEXFAILED);
    }

	// Return success
	return true;
}

/**
 * delete the module
 *
 */
function ifs_delete() {
  	// Drop tables
  	$tables = array (
  		'ifs_cache',
  		'ifs_cache_content'
		  );
	foreach ($tables as $table) {
		if (!DBUtil::dropTable($table)) {
		  	return false;
		}
	}
	// Delete module variables if there are any
	pnModDelVar('ifs');

	// Return success
	return true;
}
