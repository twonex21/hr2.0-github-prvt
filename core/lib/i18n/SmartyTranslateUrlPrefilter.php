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
                    ' ' => '-'
                );

        $string = strtr($string,$repl);

        // Return translation
        return $string;
    }
}

//EOF
?>
