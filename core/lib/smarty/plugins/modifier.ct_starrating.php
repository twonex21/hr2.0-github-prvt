<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty str_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     ct_strformat<br>
 * Purpose:  Cosmotourist string normalize
 * @author   Cosmotourist
 * @param string
 * @param string|array
 * @param string|array
 * @return string
 */
function smarty_modifier_ct_starrating($string)
{
    return $string * 20;
}

/* vim: set expandtab: */

?>
