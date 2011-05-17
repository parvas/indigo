<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class File {
	
	protected static $_instance;
	protected $_file;
	protected $_directory;
	protected $_max_size;
	protected $_types = array();
	
	public static function instance()
	{
		if (static::$_instance)
		{
			return static::$_instance;
		}
		
		return static::$_instance = new File;
	} 
	
	protected function __construct()
	{
		
	}
	
	public function upload($file, $directory, $create_dir = false)
	{
		$this->_file = $file;
		
		// always upload in preconfigured directory
		$this->_directory = APP . 'assets/uploads/' . $directory . '/';
		
		// should we create a directory to put file into?
		if ($create_dir === true && !is_dir($this->_directory))
		{
			mkdir($this->_directory);
		}
		elseif (!is_dir($this->_directory) || !is_writable($this->_directory))
		{
			Exceptions::exception("Directory '{$this->_directory}' does not exist or is not writeable");
		}
		
		if ($this->_validate())
		{
			move_uploaded_file($file['tmp_name'], $directory . $file['name']);
		}
	}
	
	public function rename($old, $new, $directory)
	{
		
	}
	
	public function rename_dir()
	{
		return $this;
	} 
	
	public function delete($file, $directory)
	{
		return $this;
	}
	
	public function max_size($size)
	{
		$this->_max_size = $size;
		return $this;
	}
	
	public function types(array $types)
	{
		$this->_types = $types;
		return $this;
	}
	
	protected function _validate()
	{
		if (isset($this->_max_size))
		{
			 if ($this->_file['size'] >= $this->_max_size)
			 {
			     return false;		
			 }
		}
		
		if (count($this->_types) > 0)
		{
			$ext = strtolower(pathinfo($this->_file['name'], PATHINFO_EXTENSION));
			
			if (!in_array($ext, $this->_types))
			{
				return false;
			}
		}
		
		return true;
	}
}