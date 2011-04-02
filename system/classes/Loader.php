<?php defined('SYSTEM') or exit('No direct script access allowed');

class Loader {
	
    private static $_class;
    private static $_method;
    private static $_modules = array();
    private static $_module;
    private static $_directory;
    
    public static function load($class)
    {
        self::$_class = '';
        self::$_directory = APP . 'modules/';
        self::$_method = 'index';
        
        if (strpos($class, '/') !== FALSE)
        {
            $segments = explode('/', $class);

            // trying to determine path in case of nested module
            foreach ($segments as $segment)
            {
                // first check if segment maps to an existing sub-directory
                if (is_dir(self::$_directory . $segment))
                {
                    self::$_directory .= $segment . '/';
                    self::$_class = $segment;
                    // each sub-module extends parent module, 
                    // so we must include path of parent
                    require self::$_directory . self::$_class . '.php';
                    
                    continue;
                }
                
                if (method_exists(ucfirst(self::$_class), $segment))
                {
                    self::$_method = $segment;
                }
            }
        }
        else
        {
            // no nesting, direct call to <module>/index
            self::$_directory .= $class . '/';
            self::$_class = $class;
            require self::$_directory . self::$_class . '.php';
        }
        
        $class = ucfirst(self::$_class);
        $method = self::$_method;
        $instance = new $class;
        $instance->$method();
        return $instance;
    }
    
    public static function find()
    {
        
    }
}