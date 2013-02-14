<?php

/**
 * set attribute
 *
 * @param	$args['name']        (string)	attribute name
 * @param	$args['value']       (string)	value of attribute
 * @return	string on success, false otherwise
 */
function ifs_attributesapi_set($args)
{
    prayer($args);
    $name  = $args['name'];
    $value = $args['value'];
    $uid   = pnUserGetVar('uid');
    // Get user
    $user = DBUtil::selectObjectByID('users',$uid);
    prayer($users);
    die();
}
