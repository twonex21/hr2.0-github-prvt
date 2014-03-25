<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty urlformat modifier plugin
 *
 * Type:     modifier<br>
 * Name:     ct_urlformat<br>
 * Purpose:  Cosmotourist url normalize
 * @author   Cosmotourist
 * @param string
 * @return string
 */
function smarty_modifier_ct_urlformat($string)
{
    return str_replace(array('_',' '),'-',strtolower($string));
}

/* vim: set expandtab: */

?>
