<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Image extends File {
	
	protected $_max_width;
	protected $_max_height;
    protected $_thumbnail_dir = 'thumbnails/';
	
	public static function instance($file = null)
	{
		if (static::$_instance)
		{
			return static::$_instance;
		}

        return static::$_instance = new Image($file);
	} 
	
	protected function __construct($file)
	{
		parent::__construct($file);
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
    
    public function create_thumbnail($thumb_dir = null)
    {
        if (!is_null($thumb_dir))
        {
            if (Filesystem::dir_check($this->_directory) ===  false)
            {
                throw new Exceptions('Directory error during upload. See log for more details.');
            }
            
            $this->_thumbnail_dir = $thumb_dir;
        }
    }
	
	public function validate()
	{
        if ($this->_pre_validate() === true)
        {
            return true;
        }
        elseif (count($this->_errors) > 0)
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
            $this->_errors[] = sprintf(I18n::instance()->line('invalid_width'), $this->_file['name']);
        }

        if (isset($this->_max_height) && $atts[1] > $this->_max_height)
        {
            $this->_errors[] = sprintf(I18n::instance()->line('invalid_height'), $this->_file['name']);
        }

        return count($this->_errors) > 0 ? false : true;
	}
}