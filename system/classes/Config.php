<?php defined('SYSTEM') or exit('No direct script access allowed');

class Config {
    
    /**
     * Array storing all configuration items
     * from loaded files.
     * 
     * @access private
     * @var array 
     */
    private $_config = array();
    
    /**
     * Array storing all loaded filenames.
     * 
     * @access private
     * @var array 
     */
    private $_loaded = array();
    
    /**
     * Stores class instance.
     * 
     * @access private
     * @var Config 
     */
    private static $_instance;
    
    
    /**
     * Singleton method for Config class.
     * 
     * @access  public
     * @return  Config
     * @static 
     */
    public static function instance()
    {
        if (static::$_instance)
        {
            return static::$_instance;
        }
        
        return static::$_instance = new Config;
    }
    
    /**
     * Loads a configuration file from application/cpnfig folder.
     * 
     * @access  public
     * @param   string $file  File to be loaded.
     * @return  Config 
     */
    public function load($file)
    {
        // config file already loaded
        if (in_array($file, $this->_loaded))
        {
            return $this;
        }
        
        // config file not loaded
        if (file_exists(APP . 'config/' . $file . '.php'))
        {
            require_once APP . 'config/' . $file . '.php';
            $this->_config = array_merge($this->_config, $config);
            
            $this->_loaded[] = $file;
            
            return $this;
        }
        
        echo 'File ' . $file . '.php not found in config folder.';
        return $this;
    }
    
    /**
     * Returns a configuration item.
     * 
     * @access  public
     * @param   string  $item
     * @return  mixed   Requested configuration item. 
     */
    public function get($item)
    {
        return $this->_config[$item];
    }
}