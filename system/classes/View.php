<?php defined('SYSTEM') or exit('No direct script access allowed');

class View {
    
    private $_contents;
    private $_data = array();
    
    public static function factory($view, $data = null)
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
        require Module::find($view, 'view');
        $this->_contents = ob_get_contents();
        ob_end_clean();
        
        return $this->_contents;
    }
}

?>
