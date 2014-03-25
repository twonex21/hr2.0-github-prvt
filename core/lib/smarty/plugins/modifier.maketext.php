<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty maketext modifier plugin
 *
 * Type:     modifier<br>
 * Name:     maketext<br>
 * Purpose:  handle maketext
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @return string
 */
function smarty_modifier_maketext($number,$string)
{
    //$string = '['.$function.','.$count.','.$singular.','.$plural.']';

	return i18nMaketext::maketext($string,$number);
}

//[mtt quant,_$solr.length,document,documents]
/*
 $num_cats = 2;
	 *    $num_mats = 1;
	 *
	 *    $mt->maketext(
	 *       '[quant,_1,cat,cats] sat on the [numerate,_2,mat,mats].',
	 *       $num_cats, $num_mats);
    */
?>
