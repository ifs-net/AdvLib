<?php

/**
 * get cached content of a module
 *
 * @param	$args['modname']	(string)	module name
 * @param	$args['cid']		(string)	used cache id
 * @return	string on success, false otherwise
 */
function ifs_cacheapi_get($args)
{
  	$cid = 		(string)	$args['cid'];
  	$modname = 	(string)	$args['modname'];
  	$mid = pnModGetIDFromName($modname);
  	
  	// Little checks
  	if (
	  	(!($mid > 0))
	  	||
	  	(!isset($cid)		|| ($cid == ''))
	  	) {
		return false;
	}
  	$where = 'mid = '.$mid." AND cid = '".$cid."'";
	$joinInfo[] = array (	'join_table'          =>  'ifs_cache_content',	// table for the join
							'join_field'          =>  'content',			// field in the join table that should be in the result with
                         	'object_field_name'   =>  'content',			// ...this name for the new column
                         	'compare_field_table' =>  'id',					// regular table column that should be equal to
                         	'compare_field_join'  =>  'id');				// ...the table in join_table
  	
   	$result = DBUtil::selectExpandedObject('ifs_cache',$joinInfo,$where);
   	if ($result) {
   	  	// Delete Object if it is invalid up to now
		if ($result['vu'] < time()) {
		  	$delRes = DBUtil::deleteObjectByID('ifs_cache_content',$result['id']);
		  	if (!$delRes) {
			    return false;
			} else {
			  	$delRes = DBUtil::deleteObjectByID('ifs_cache',$result['id']);
			  	return false;
			}
		} else {
		  	// return Object
		  	return $result['content'];
		}
	} else {
	  	return false;
	}
}

/**
 * set content into cache for a module
 *
 * @param	$args['modname']	(string)	module name
 * @param	$args['cid']		(string)	used cache id
 * @param	$args['sec']		(int)		seconds to use for cache
 * @param	$args['content']	(string)	seconds to use for cache
 * @return	string on success, false otherwise
 */
function ifs_cacheapi_set($args)
{
  	$sec = 		(int)		$args['sec'];
  	$cid = 		(string)	$args['cid'];
  	$modname = 	(string)	$args['modname'];
  	$content = 	(string)	$args['content'];
  	$mid = pnModGetIDFromName($modname);

  	// Little checks
  	if (
	  	(!($mid > 0))
	  	||
	  	(!isset($cid)		|| ($cid == ''))
	  	||
	  	(!isset($content)	|| ($content == ''))
	  	||
	  	(!($sec > 0))
	  	) {
		return false;
	}

	// Check for existing cached content
  	$where = 'mid = '.$mid." AND cid = '".$cid."'";
  	$result = DBUtil::selectObjectCount('ifs_cache',$where);
  	if ($result > 0) {
	    return false;
	}
	
	$vu = time()+$sec;

	// Built cached object
  	$cache_obj = array (
  			'cid'		=> $cid,
  			'mid'		=> $mid,
  			'content'	=> $content,
  			'vu'		=> $vu
		);

   	$result = DBUtil::insertObject($cache_obj,'ifs_cache');
   	if (!$result) {
	     return false;
	}
	$cache_content_obj = array (
			'id'		=> $cache_obj['id'],
			'content'	=> $content
		);
	$result = DBUtil::insertObject($cache_content_obj,'ifs_cache_content','id',true);
	return $result;
}

/**
 * delete cached content
 *
 * @param	$args['modname']	(string)	module name
 * @param	$args['cid']		(string)	used cache id, optional
 * @return	boolean
 */
function ifs_cacheapi_del($args)
{
  	$cid = 		(string)	$args['cid'];
  	$modname = 	(string)	$args['modname'];
  	$mid = pnModGetIDFromName($modname);
  	
  	// Check parameters
  	if (!($mid > 0)) {
	    return false;
	}
	
	// construct where for deletion process
	$w = array();
	$w[] = 'mid = '.$mid;
	if (isset($cid) && ($cid != '')) {
	  	$w[] = "cid = '".DataUtil::formatForStore($cid)."'";
	}
	$where = implode(' AND ',$w);
	
	// delete all cached contents
	$result = DBUtil::selectObjectArray('ifs_cache',$where);
	foreach ($result as $item) {
	  	$content = DBUtil::selectObjectByID('ifs_cache_content',$item['id']);
	  	$res = DBUtil::deleteObject($content,'ifs_cache_content');
	  	if ($res) {
		    $res = DBUtil::deleteObject($item,'ifs_cache');
		    if (!$res) {
			  	return false;
			}
		} else {
		  	return false;
		}
	}
	
	// Return succes
	return true;
}