<?php defined('SYSTEM') or exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Template
 *
 * @author parvas
 */
class Template {
    
    private static $_instance;
    private $_head;
    private $_description;
    private $_keywords;
    private $_title;
    private $_body;
    private $_data = array();
    
    /**
     *
     * @return Template
     */
    public static function instance()
    {
        if (static::$_instance)
        {
            return static::$_instance;
        }
        
        return static::$_instance = new Template;
    }
    
    
    /**
     *
     * @param type $region
     * @param type $value
     * @return Template 
     */
    public function __set($region, $value)
    {
        $this->$region = $value;
        return $this;
    }
    
    /**
     *
     * @param type $value
     * @return Template 
     */
    public function title($value)
    {
        $this->_title = $value;
        return $this;
    }
    
    /**
     *
     * @param type $value
     * @return Template 
     */
    public function head($value)
    {
        $this->_head = $value;
        return $this;
    }
    
    /**
     *
     * @param type $value
     * @return Template 
     */
    public function description($value)
    {
        $this->_description = $value;
        return $this;
    }
    
    /**
     *
     * @param type $value
     * @return Template 
     */
    public function keywords($value)
    {
        $this->_keywords = is_array($value) ? implode(', ', $value) : $value;
        return $this;
    }
    
    public function set_region()
    {
        
    }
    
    public function render($view, $data = array())
    {
        $this->_body .= View::factory($view, $data)->render();

        if (Module::is_master())
        {
            $this->_data = array(
                'head'        => $this->_head,
                'description' => $this->_description,
                'keywords'    => $this->_keywords,
                'title'       => $this->_title,
                'body'        => $this->_body
                    );
        
            extract($this->_data);
            //ob_start();

            require APP . 'views/templates/default.php';
           // $result = ob_get_contents();
            //ob_end_clean();
            //echo $result;
        }
    }
    
    
}