<?php

/**
 * Populate tables array for the module
 *
 * @return       array       The table information.
 */
function ifs_pntables()
{
    // Initialise table array
    $table = array();

	// Get Table Prefix
    $ifs_cache         = DBUtil::getLimitedTablename('ifs').'_cache';
    $ifs_cache_content = DBUtil::getLimitedTablename('ifs').'_cache_content';

    $table['ifs_cache'] = $ifs_cache;
	$table['ifs_cache_content'] = $ifs_cache_content;

    // Columns for tables
    $table['ifs_cache_column'] = array (
    			'id'					=> 'id',
    			'mid'					=> 'mid',
    			'cid'					=> 'cid',
    			'vu'					=> 'vu'
    			);
    $table['ifs_cache_column_def'] = array (
    			'id'					=> "I AUTOINCREMENT PRIMARY",
    			'mid'					=> "I NOTNULL DEFAULT 0",
    			'cid'					=> "C(25) NOTNULL DEFAULT 0",
    			'vu'					=> "I(12) NOTNULL DEFAULT 0"
    			);
    $table['ifs_cache_content_column'] = array (
    			'id'					=> 'id',
    			'content'				=> 'content'
    			);
    $table['ifs_cache_content_column_def'] = array (
    			'id'					=> "I PRIMARY",
    			'content'				=> "XL NOTNULL"
    			);

	// Return table information
	return $table;
}
