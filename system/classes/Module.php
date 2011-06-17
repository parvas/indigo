<?php defined('SYSTEM') or exit('No direct script access allowed');

class Module {
    
    /**
     * @access private
     * @var string Module classname. 
     * @static
     */
    private static $_class;
    
    /**
     * @access private
     * @var string Module method name. 
     * @static
     */
    private static $_method;
    
    /**
     * @access private
     * @var array Loaded modules stack. 
     * @static
     */
    private static $_modules = array();
    
    /**
     * @access private
     * @var string Current module name. 
     * @static
     */
    private static $_module;
    
    /**
     * @access private
     * @var string Module directory.
     * @static
     */
    private static $_directory;
    
    /**
     * @access private
     * @var array Module method parameters. 
     * @static
     */
    private static $_params = array();
    
    /**
     * @access private
     * @var array Contains $_POST values associated *only* with current module. 
     * @static
     */
    private $_post = array();
    
    /**
     * @access private
     * @var array Contains $_GET values associated *only* with current module.
     * @static
     */
    private $_get = array();
    
    /**
     * @access private
     * @var array Contains $_FILES values associated *only* with current module.
     * @static
     */
    private $_files = array();

    /**
     * Returns current module instance.
     * 
     * @access public
     * @return Module  Current module instance.
     * @static
     */
    public static function instance()
    {
        return new static;
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
        
        // execute the "before" tasks
        $instance->before();
        
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
        
        // execute all "after" tasks
        $instance->after();
        
        static::_remove_from_stack();
    }
    
    /**
     * Class constructor.
     * Initializes global arrays.
     */
    private function __construct()
    {
        // never use $_POST, $_GET, $_FILES directly!!!
        $this->_post = $_POST;
        $this->_get = $_GET;
        $this->_files = $_FILES;
    }
    
    /**
     * Global $_POST array handler. May act as getter or setter.
     * 
     * @access public
     * @return mixed      Returns whole $_POST array, one item of it, or sets a new value. 
     * @uses   Arr::item  Handles global array request.    
     */
    public function post($index = null, $value = null)
    {
        return Arr::item($this->_post, $index, $value);
    }
    
    /**
     * Global $_GET array handler. May act as getter or setter.
     * 
     * @access public
     * @return mixed      Returns whole $_GET array, one item of it, or sets a new value. 
     * @uses   Arr::item  Handles global array request.    
     */
    public function get($index = null, $value = null)
    {
        return Arr::item($this->_get, $index, $value);
    }
    
    /**
     * Global $_FILES array handler. May act as getter or setter.
     * 
     * @access public
     * @return mixed      Returns whole $_FILES array, one item of it, or sets a new value. 
     * @uses   Arr::item  Handles global array request.    
     */
    public function files($index = null, $value = null)
    {
        return Arr::item($this->_files, $index, $value);
    }
    
    /**
     * Finds a file associated with a module.
     * Searches for one of the following: controller, view, model.
     * 
     * @access public
     * @param  string $item  	 Relative path of file.
     * @param  string $type  	 Type of file to be loaded (controller, view, model).
     * @return string $filename  Absolute path of file to be loaded.
     * @static  
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
     * Adds a new entry in module stack.
     * 
     * @access private
     * @static
     */
    private static function _add_to_stack()
    {
        static::$_modules[] = static::$_class;
    }
    
   /**
     * Removes entry from module stack when done with module.
     * 
     * @access private
     * @static
     */
    private static function _remove_from_stack()
    {
        array_pop(static::$_modules);
        static::$_module = end(static::$_modules);
    }
    
    /**
     * Checks if current module is the initial request.
     * 
     * @access public
     * @return boolean  True if request is initial, false otherwise.
     * @static  
     */
    public static function is_master()
    {
        return count(static::$_modules) === 1;
    }
    
    /**
     * Returns name of currently active module.
     * 
     * @access public
     * @return string  Name of currently active module.
     * @static 
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
     * @return boolean  True if number of parameters is correct, false otherwise.
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