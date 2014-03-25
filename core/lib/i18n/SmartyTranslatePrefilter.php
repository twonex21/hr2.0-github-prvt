<?php

class SmartyTranslatePrefilter
{
    static function translatePrefilter($tpl_source, &$smarty)
    {
        //parse the template tags
        return preg_replace_callback('/{tr}(.*){\/tr}/ismU',array(
                        'SmartyTranslatePrefilter','translate'),$tpl_source);
    }

    static function translate($text)
    {
        // Return translation

        return gettext($text[1]);
    }
    

}

//EOF
?>
