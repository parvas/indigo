<?php defined('SYSTEM') or exit('No direct script access allowed');

class Module {
    
    private static $_class;
    private static $_method;
    private static $_modules = array();
    private static $_module;
    private static $_directory;
    private static $_params = array();
    private static $_instance;
    
    private $_post = array();
    private $_get = array();
    private $_files = array();

    /**
     *
     * @return Module
     */
    public static function instance()
    {
        return static::$_instance;
    }
    
    /**
     * Creates a new instance of the requested module and invokes requested method.
     * New module instances should only be created buy using this method.
     * Also used for HMVC calls, both internal and external.
     * 
     *      Usage: Module::factory('controller/method/params');
     * 
     * @access public
     * @param string $module  The module resource to be loaded.
     * @static
     */
    public static function factory($module)
    {
        static::$_module = '';
        static::$_class = '';
        static::$_directory = APP . 'modules/';
        static::$_method = 'index';
        
        if (strpos($module, '/') !== FALSE)
        {
            $segments = explode('/', $module);

            // trying to determine path in case of nested module
            foreach ($segments as $segment)
            {
                // first check if segment maps to an existing sub-directory
                if (is_dir(static::$_directory . $segment))
                {
                    // digging deeper into the filesystem
                    static::$_directory .= $segment . '/';
                    static::$_class = $segment;
                    static::$_module .= $segment;
                    // include parent class
                    require_once static::$_directory . static::$_class . '.php';
                    // path segment not fully parsed yet, so loop again
                    continue;
                }
                
                // url class segment parsed, but no directory found 
                if (static::$_class == '')
                {
                    Exceptions::error_404(URL::fetch_full());
                }
                
                // time to check if this segment maps to a valid controller method
                if (static::$_method == 'index' && method_exists(ucfirst(static::$_class), $segment))
                {
                    static::$_method = $segment;
                }
                else 
                {
                    static::$_params[] = $segment;
                }
            }
        }
        else
        {
            // no nesting, direct call to <module>/index
            if (!is_dir(static::$_directory . $module))
            {
                Exceptions::error_404(URL::fetch_full());
            }
            
            static::$_directory .= $module . '/';
            static::$_class = static::$_module = $module;
        }
        
        // directory (and class) is set, include class file.
        require_once static::$_directory . static::$_class . '.php';
        
        // was the number of parameters acceptable?
        if (static::_check_params() === false)
        {
            Exceptions::error_404(URL::fetch_full());
        }
        
        $class = ucfirst(static::$_class);
        $instance = new $class;
        static::_add_to_stack();
        static::$_instance = new Module();
        
        // pseudo-static method invocation instead of call_user_func()
        switch (count(static::$_params))
        {
            case 0:
                $instance->{static::$_method}();
                break;
            case 1:
                $instance->{static::$_method}(static::$_params[0]);
                break;
            case 2:
                $instance->{static::$_method}(static::$_params[0], static::$_params[1]);
                break;
            case 3:
                $instance->{static::$_method}(static::$_params[0], static::$_params[1], static::$_params[2]);
                break;
        }
        
        static::_remove_from_stack();
    }
    
    private function __construct()
    {
        $this->_post = $_POST;
        $this->_get = $_GET;
        $this->_files = $_FILES;
    }
    
    public function post($index = null, $value = null)
    {
        if (!is_null($index))
        {
            if (!is_null($value))
            {
                $this->_post[$index] = $value;
                return;
            }

            return isset($this->_post[$index]) ? $this->_post[$index] : '';
        }
        
        return $this->_post;
    }
    
    public function get()
    {
        if (!is_null($index))
        {
            if (!is_null($value))
            {
                $this->_get[$index] = $value;
                return;
            }
            
            return isset($this->_get[$index]) ? $this->_get[$index] : '';
        }
        
        return $this->_get;
    }
    
    public function files()
    {
        if (!is_null($index))
        {
            if (!is_null($value))
            {
                $this->_files[$index] = $value;
                return;
            }
            
            return isset($this->_files[$index]) ? $this->_files[$index] : '';
        }
        
        return $this->_files;
    }
    
    /**
     *
     * @param type $item
     * @param type $type
     * @return string 
     */
    public static function find($item, $type = 'module')
    {
        static::$_directory = APP . 'modules/';
        
        if (strpos($item, '/') !== FALSE)
        {
            $segments = explode('/', $item);
            
            foreach ($segments as $segment)
            {
                if (is_dir(static::$_directory . $segment))
                {
                    static::$_directory .= $segment . '/';
                    continue;
                }
            }
        }
        else 
        {
            if ($type === 'view')
            {
                static::$_directory .= static::current() . '/';
            }
            
            $segment = $item;
        }
        
        switch ($type)
        {
            case 'model':
                $filename = static::$_directory . $segment . '/' . $segment . '_model.php';
                break;

            case 'view':
                $filename = static::$_directory . 'views/' . $segment . '.php';
                break;

            case 'module':
                break;

            default:
                throw new Exceptions('Undefined file type');
        }
        
        if (!file_exists($filename))
        {
            throw new Exceptions("Requested file '{$filename}' not found");
        }

        return $filename;
    }
    
    /**
     * 
     */
    private static function _add_to_stack()
    {
        static::$_modules[] = static::$_class;
    }
    
    /**
     * 
     */
    private static function _remove_from_stack()
    {
        array_pop(static::$_modules);
        static::$_module = end(static::$_modules);
    }
    
    /**
     *
     * @return bool 
     */
    public static function is_master()
    {
        return count(static::$_modules) === 1;
    }
    
    /**
     *
     * @return type 
     */
    public static function current()
    {
        return static::$_module;
    }
    
    /**
     * Checks whether the number of parameters provided for the method matches 
     * the actual parameter number in method definition.
     * 
     * This method saves us the touble of checking for parameter validity
     * inside every controller's methods.
     * 
     * @access private
     * @return bool    true if number of parameters is correct, false otherwise.
     * @static
     */    
    private static function _check_params()
    {
        $method = new ReflectionMethod(ucfirst(static::$_class), static::$_method);
        
        $min    = $method->getNumberOfRequiredParameters();
        $max    = $method->getNumberOfParameters();
        $actual = count(static::$_params);
        
        return ($actual < $min || $actual > $max) ? false : true;
    }
}