<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Image extends File {
	
	protected $_max_width;
	protected $_max_height;
	
	public static function factory($field, $remove_whitespace = false)
	{
        return new Image($field, $remove_whitespace);
	} 
	
	protected function __construct($field, $remove_whitespace)
	{
		parent::__construct($field, $remove_whitespace);
	}

	public function set_max_dimensions($width, $height)
	{
		$this->_max_width = $width;
		$this->_max_height = $height;
        
		return $this;
	}
	
	public function set_max_width($width)
	{
		$this->_max_width = $width;
        
		return $this;
	}
	
	public function set_max_height($height)
	{
		$this->_max_height = $height;
        
		return $this;
	}
    
	public function validate()
	{
        if ($this->_pre_validate() === true)
        {
            return true;
        }
        elseif (count(static::$_errors) > 0)
        {
            // PHP error, $_FILES array not populated
            return false;
        }
        
        $this->_validate_size();
        $this->_validate_types();

        /*
		 * $atts[0] -> width, $atts[1] -> height
		 */
		$atts = @getimagesize($this->_file['tmp_name']);
        
        if (isset($this->_max_width) && $atts[0] > $this->_max_width)
        {
            static::$_errors[] = sprintf(I18n::instance()->line('invalid_width'), $this->_file['name']);
        }

        if (isset($this->_max_height) && $atts[1] > $this->_max_height)
        {
            static::$_errors[] = sprintf(I18n::instance()->line('invalid_height'), $this->_file['name']);
        }

        return count(static::$_errors) > 0 ? false : true;
	}
}