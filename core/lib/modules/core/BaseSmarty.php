<?php
namespace HR\Core;

require LIB_DIR.'i18n/i18nMaketext.php';
require LIB_DIR.'smarty/Smarty.class.php';
require LIB_DIR.'smarty/SmartyValidate.class.php';
require LIB_DIR.'i18n/SmartyTranslatePrefilter.php';

use \Smarty as Smarty;

class BaseSmarty extends Smarty
{
    public $runAsPlugin = false;

    public $lang = '';

    public $mainTemplate = BASE_MAIN_TEMPLATE;
    public $popupTemplate = BASE_POPUP_TEMPLATE;

    public $blocks = array();
    
    private static $_instance = null;

    protected function __construct($language, $mainTpl="")
    {
        //store language
        $this->lang = $language;

        //every view must use smarty
        //construct the smarty object here
        //and set the language the output is in as compile_id
        $this->Smarty();
		
		
		//Edgar
		$this->mainTemplate = empty($mainTpl)?BASE_MAIN_TEMPLATE:$mainTpl;

        $this->template_dir = BASE_TEMPLATE_DIR;
        $this->config_dir = BASE_CONFIG_DIR;
        $this->cache_dir = BASE_CACHE_DIR;
        $this->compile_dir = BASE_TEMPLATE_C_DIR;

        //set language to create a compiled template for each language
        $this->compile_id = $this->lang;

        //$this->compile_check = false;
        $this->force_compile = false;

        $this->register_prefilter(array('SmartyTranslatePrefilter','translatePrefilter'));                      

        //$this->caching = false;
    }

    private function __clone() {}
 
    static public function getInstance($language, $mainTpl="") {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self($language, $mainTpl);
        }
        return self::$_instance;
    }

    public function plugin($params,$smarty)
    {
        //set
        $this->runAsPlugin = true;

        //caching of plugin output        
        try{
            $GLOBALS['fC']->delegate($params['controller'],$params['action'],$params);
        } catch(BaseException $e){
            //should store message here
        }

        //reset
        $this->runAsPlugin = false;
    }
}
?>
