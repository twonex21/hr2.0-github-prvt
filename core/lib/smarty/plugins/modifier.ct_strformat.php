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
function smarty_modifier_ct_strformat($string)
{
    $string = trim(utf8_decode($string));
    $string = strtolower($string);
    
    $repl = array(
                    '�' => 'ae',
                    '�' => 'a',
                    '�' => 'a',
                    '�' => 'a',
                    '�' => 'a',
                    '�' => 'a',
                    '�' => 'ae',
                    '�' => 'c',
                    '�' => 'e',
                    '�' => 'e',
                    '�' => 'e',
                    '�' => 'e',
                    '�' => 'i',
                    '�' => 'i',
                    '�' => 'i',
                    '�' => 'i',
                    '�' => 'n',
                    '�' => 'o',
                    '�' => 'o',
                    '�' => 'o',
                    '�' => 'o',
                    '�' => 'o',
                    '�' => 'u',
                    '�' => 'u',
                    '�' => 'u',
                    '�' => 'y',
                    '�' => 'oe',
                    '�' => 'ue',
                    '�' => 'ss',
                    '&' => '-',
                    ',' => '',
                    ' ' => '-',
                    '&amp;' => '-',
                    '_' => '-',
                    '(' => '',
                    ')' => ''
                );

    return strtr($string,$repl);
}
?>