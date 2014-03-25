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
                    'ä' => 'ae',
                    'á' => 'a',
                    'à' => 'a',
                    'â' => 'a',
                    'å' => 'a',
                    'ã' => 'a',
                    'æ' => 'ae',
                    'ç' => 'c',
                    'é' => 'e',
                    'è' => 'e',
                    'ê' => 'e',
                    'ë' => 'e',
                    'í' => 'i',
                    'ì' => 'i',
                    'î' => 'i',
                    'ï' => 'i',
                    'ñ' => 'n',
                    'ó' => 'o',
                    'ò' => 'o',
                    'ô' => 'o',
                    'ø' => 'o',
                    'õ' => 'o',
                    'ú' => 'u',
                    'ù' => 'u',
                    'û' => 'u',
                    'ÿ' => 'y',
                    'ö' => 'oe',
                    'ü' => 'ue',
                    'ß' => 'ss',
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