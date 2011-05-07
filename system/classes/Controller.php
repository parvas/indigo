<?php defined('SYSTEM') or exit('No direct script access allowed');

abstract class Controller {
    
    /**
     * @access pretected
     * @var Model 
     * 
     * Current module model instance.
     * If needed (HMVC), other models may be initialized 
     * via Model::factory(<module_name>). 
     */
    protected $model;
    
    /**
     * @access protected
     * @var Template Template instance. 
     */
    protected $template;
    
    /**
     * @access protected
     * @var Module Current module instance. 
     */
    protected $module;
    
    /**
     * @access protected
     * @var array Contains all data to be passed from/to views and models.
     */
    protected $data = array();
    
    /**
     * Controller constructor.
     * Initializes current template and model.
     */
    public function __construct()
    {
        $this->template    = Template::instance();
        $this->model       = Model::factory();
        $this->module      = Module::instance(); 
    }
}