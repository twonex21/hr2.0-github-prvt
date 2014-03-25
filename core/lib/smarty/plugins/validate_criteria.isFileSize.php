<?php

/**
 * Project:     SmartyValidate: Form Validator for the Smarty Template Engine
 * File:        validate_criteria.isFileSize.php
 * Author:      Monte Ohrt <monte at newdigitalgroup dot com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @link http://www.phpinsider.com/php/code/SmartyValidate/
 * @copyright 2001-2005 New Digital Group, Inc.
 * @author Monte Ohrt <monte at newdigitalgroup dot com>
 * @package SmartyValidate
 * @version 2.6
 */

/**
 * test if a value is a valid file size.
 *
 * @param string $value the value being tested
 * @param boolean $empty if field can be empty
 * @param array params validate parameter values
 * @param array formvars form var values
 */
function smarty_validate_criteria_isFileSize($value, $empty, &$params, &$formvars) {
    $_field = $params['field'];
    $_max = isset($params['field2']) ? $params['field2'] : trim($params['max']);
    $_min = isset($params['field3']) ? $params['field3'] : '0b';

    if(!isset($_FILES[$_field]))
        // nothing in the form
        return false;
    
    if($_FILES[$_field]['error'] == 4)
        // no file uploaded
        return $empty;

    if(!isset($_max)) {
        trigger_error("SmartyValidate: [isFileSize] 'max' attribute is missing.");        
        return false;           
    }
    
    if(!preg_match('!^(\d+)([bkmg](b)?)?$!i', $_max, $_match)) {
        trigger_error("SmartyValidate: [isFileSize] 'max' attribute is invalid.");        
        return false;   
    }
    
    $_size = $_match[1];
    $_type = strtolower($_match[2]);
    
    switch($_type) {
        case 'k':
            $_maxsize = $_size * 1024;            
            break;
        case 'm':
            $_maxsize = $_size * 1024 * 1024;            
            break;
        case 'g':
            $_maxsize = $_size * 1024 * 1024 * 1024;
            break;
        case 'b':
        default:
            $_maxsize = $_size;
            break;   
    }

    if(!preg_match('!^(\d+)([bkmg](b)?)?$!i', $_min, $_match)) {
        trigger_error("SmartyValidate: [isFileSize] 'max' attribute is invalid.");        
        return false;   
    }
    
    $_size = $_match[1];
    $_type = strtolower($_match[2]);
    
    switch($_type) {
        case 'k':
            $_minsize = $_size * 1024;            
            break;
        case 'm':
            $_minsize = $_size * 1024 * 1024;            
            break;
        case 'g':
            $_minsize = $_size * 1024 * 1024 * 1024;
            break;
        case 'b':
        default:
            $_minsize = $_size;
            break;   
    }    
    
    if( $_minsize <= $_FILES[$_field]['size'] && $_FILES[$_field]['size'] <= $_maxsize)
        return true;
    else
        return false;
}

?>
