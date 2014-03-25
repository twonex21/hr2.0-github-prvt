<?php

class SmartyTranslateUrlPrefilter
{
    static function translateUrlPrefilter($tpl_source, &$smarty)
    {
        //parse the template tags
        return preg_replace_callback('/{trurl}(.*){\/trurl}/ismU',array(
                        'SmartyTranslateUrlPrefilter','translate'),$tpl_source);
    }

    static function translate($text)
    {
        $string = gettext($text[1]);

        $string = utf8_decode($string);
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
                    ' ' => '-'
                );

        $string = strtr($string,$repl);

        // Return translation
        return $string;
    }
}

//EOF
?>
