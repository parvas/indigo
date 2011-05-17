<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Image extends File {
	
	private $_max_width;
	private $_max_height;
	
	public static function instance()
	{
		if (static::$_instance)
		{
			return static::$_instance;
		}
		
		return static::$_instance = new Image;
	} 
	
	protected function __construct()
	{
		parent::__construct();
	}

	public function max_dimensions($width, $height)
	{
		$this->_max_width = $width;
		$this->_max_height = $height;
		return $this;
	}
	
	public function max_width($width)
	{
		$this->_max_width = $width;
		return $this;
	}
	
	public function max_height($height)
	{
		$this->_max_height = $height;
		return $this;
	}
	
	protected function _validate()
	{
		/*
		 * $atts[0] : width
		 * $atts[1] : height
		 */
		$atts = getimagesize($this->_file['tmp_name']);
		
		if (isset($this->_max_width))
		{
			 if ($this->_file[0] >= $this->_max_width)
			 {
			     return false;		
			 }
		}
		
		if (isset($this->_max_height))
		{
			 if ($this->_file[1] >= $this->_max_height)
			 {
			     return false;		
			 }
		}
		
		parent::_validate();
	}
}