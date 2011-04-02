<?php defined('SYSTEM') or exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of view
 *
 * @author parvas
 */
class View {
    
    private $_contents;
    private $_data = array();
    
    public static function factory($view, $data = NULL)
    {
        return new View($view, $data);
    }
    
    public function render()
    {
        return $this->_contents;
    }
    
    public function __construct($view, $data) 
    {
        $this->_data = $data;
        return $this->_find($view, $data);
    }
    
    private function _find($view, $data)
    {
        ob_start();
        
        extract($data);
        require Module::find($view);
        $this->_contents = ob_get_contents();
        
        ob_end_clean();
        
        return $this->_contents;
    }
}

?>
