<?php

/**
 * get attributes
 *
 * @param	$args['name']        (string)	attribute name
 * @return	string on success, false otherwise
 */
function ifs_attributesapi_get($args)
{
    static $ifs_attributes_cache;

    $name  = $args['name'];
    $uid   = pnUserGetVar('uid');

    // Is there something cached?
    if (isset($ifs_attributes_cache) && (count($ifs_attributes_cache[$uid]) > 0)) {
        return $ifs_attributes_cache[$uid][$name];
    }

    // Get user
    $user = DBUtil::selectObjectByID('users', $uid, 'uid', null, null, null, false);
    $attributes = $user['__ATTRIBUTES__'];
    
    // return all if no name is given
    if (!isset($name) && ($name == '')) {
        return $attributes;
    }

    // Cache all attributes
    $ifs_attributes_cache[$uid] = $attributes;
    
    // Return attribute value
    return $ifs_attributes_cache[$uid][$name];
}

/**
 * set attribute
 *
 * @param	$args['name']        (string)	attribute name
 * @param	$args['value']       (string)	value of attribute
 * @return	string on success, false otherwise
 */
function ifs_attributesapi_set($args)
{
    $name  = $args['name'];
    $value = $args['value'];
    $uid   = pnUserGetVar('uid');

    // Get user
    $user = DBUtil::selectObjectByID('users', $uid, 'uid', null, null, null, false);

    // Set Attribute
    $user['__ATTRIBUTES__'][$name] = $value;

    // Update User
    $result = DBUtil::updateObject($user,'users','','uid');
    if (!$result) die("error");
    return $result;
}

/**
 * delete attribute
 *
 * @param	$args['name']        (string)	attribute name
 * @return	string on success, false otherwise
 */
function ifs_attributesapi_del($args)
{
    $name  = $args['name'];
    $uid   = pnUserGetVar('uid');

    // Get user
    $user = DBUtil::selectObjectByID('users', $uid, 'uid', null, null, null, false);

    // Set Attribute
    unset($user['__ATTRIBUTES__'][$name]);

    // Update User
    $result = DBUtil::updateObject($user,'users','','uid');
    return $result;
}
