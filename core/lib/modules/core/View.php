<?php
namespace HR\Core;

class View
{
	private static $_instance = null;
	protected $session = array();
    protected $locale = null;
        
    public $smarty = null;

    public $template = '';        
    
    public $mainTemplate = BASE_MAIN_TEMPLATE;
    public $popupTemplate = BASE_POPUP_TEMPLATE;
        
    protected $templateDir = '';
    public $blocks = array();

    /**
     * Constructor. Must be called by constuctor of extending class
     */
    public function __construct($namespace = '', &$sessionArray = array(), $layoutTpl = '') {
    	$this->session = &$sessionArray;
		$this->locale = $GLOBALS['locale'];
        
		$this->mainTemplate = empty($mainTpl)? BASE_MAIN_TEMPLATE : $layoutTpl;
        $this->smarty = BaseSmarty::getInstance($GLOBALS['locale'], $layoutTpl);                
        
        if(!empty($namespace)) {
        	$parts = explode('\\', $namespace);    	
    		$controller = $parts[count($parts) - 1];
    		if(is_dir(FRONTEND_DIR . $controller . '/templates')) {
    			$this->templateDir = '/modules/frontend/' . $controller . '/templates';
    		}
        }
        
        $this->smarty->register_object('hr', $this);        
    }

    
    static public function getInstance($namespace = '', $sessionArray = array(), $layoutTpl = '') {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self($namespace, $sessionArray, $layoutTpl);
        }
        return self::$_instance;
    }
    
    
    /**
     * Loads a template and puts it into the template variable
     * @param string templatefile The template to be loaded
     * @param string block The block the subtemplate is assigned to
     */
    public function render($templatefile, $block = null) {    	    	
        // Make sure we have the correct locale set
        setlocale(LC_ALL,$this->locale);
        
        $this->assign('_HR_SESSION', $this->session);
                   
        if($block != null) {
            if(!isset($this->blocks[$block]))
            {
                $this->blocks[$block] = array();
            }
            array_unshift($this->blocks[$block], $this->fetch($templatefile));
        } else {
            echo $this->fetch($templatefile);
        }
    }
    
    public function block($content, $block = null)
    {
        if($block != null)
        {
            if(!isset($this->blocks[$block]))
            {
                $this->blocks[$block] = array();
            }
            array_unshift($this->blocks[$block], $content);
        } else {
            echo $content;
        }
    }

    public function show()
    {
        $this->finish();
    }

    /**
     * When a modules view is finished this method takes care of displaying the
     * main template to the user.
     * May send special headers here
     */
    public function finish()
    {
        // make sure we have the correct locale set
        setlocale(LC_ALL,$this->locale);

        foreach($this->blocks as $block => $template_array) {
            $final_value = '';
            foreach($template_array as $k => $template_value) {
                $final_value .= $template_value;
            }
            $this->smarty->assign($block,$final_value);
        }

        //display the output, use the main template        
        if($this->smarty->runAsPlugin == false)
        {
            $this->smarty->display($this->mainTemplate);
        }
    }
    
    public function finishPopUp()
    {
        foreach($this->blocks as $block => $template_array) {
            $final_value = '';
            foreach($template_array as $template_value){
                $final_value .= $template_value;
            }
            $this->smarty->assign($block,$final_value);
        }

        //display the output, use the main template
        if($this->smarty->runAsPlugin == false) {
            $this->smarty->display($this->popupTemplate);
        }
    }
    
    public function assign($name, $variable = null) {
        $this->smarty->assign($name,$variable);
    }

    public function setTemplate($templatePath) {
        $this->template = $templatePath;
    }

    /**
     * Fetch template from smarty and returns rendered value
     * @param string templatefile The template to be loaded
     */
    public function fetch($templatefile) {
    	$templatefile = $this->templateDir . '/' . $templatefile;
    	
        // make sure we have the correct locale set
        setlocale(LC_ALL,$this->locale);

        return $this->smarty->fetch(LIB_DIR . $templatefile,null);
    }
    
    public function plugin($params, $smarty) {
        //set
        $this->runAsPlugin = true;

        //caching of plugin output        
        try{
            $GLOBALS['fC']->delegate($params['controller'], $params['action'],$params);
        } catch(BaseException $e){
            //should store message here
        }

        //reset
        $this->runAsPlugin = false;
    }
    
    
    
    /* Generic display functions */
    
    public function renderBlock($block, $template, $dataArray=array()) {
    	foreach($dataArray as $key => $value) {
			$this->assign("_".$key, $value);
		}
		
        $this->render($template, $block);
    }
    
    /*
    public function showVideos() {
        $this->render('modules/frontend/Main/templates/content/videos.tpl', "CONTENT");
    } 
    */  
                
    function loadVideos($videos, $isLanding=false) {
    	$this->assign("_videos", $videos);
		$template = ($isLanding) ? "modules/frontend/Main/templates/landing_videos.tpl" : "modules/frontend/Main/templates/content/videos-view.tpl" ;
        $this->render($template);
    } 
    
    function loadProducts($products) {
    	$this->assign("_products", $products);
        $this->render('modules/frontend/Main/templates/content/products.tpl');
    }  
       
    function loadUsers($users) {
    	$this->assign("_users", $users);
        $this->render('modules/frontend/Main/templates/content/users.tpl');
    }  
    
       
    function loadBrands($brands) {
    	$this->assign("_brands", $brands);
        $this->render('modules/frontend/Main/templates/content/brands.tpl');
    }   
}
//EOF
?>
