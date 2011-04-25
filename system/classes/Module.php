<?php defined('SYSTEM') or exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of module
 *
 * @author parvas
 */
class Module {
    
    private static $_class;
    private static $_method;
    private static $_modules = array();
    private static $_module;
    private static $_directory;
    private static $_params = array();

    /**
     * 
     * 
     * @param type $module
     * @return class 
     */
    public static function factory($module)
    {
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
                    
                    // include parent class
                    require_once static::$_directory . static::$_class . '.php';
                    // path segment not fully parsed yet, so loop again
                    continue;
                }
                
                // url class segment parsed, but no directory found 
                if (static::$_class == '')
                {
                    Exceptions::error_404(WEB . $module);
                }
                // directory (and class) is set, include class file.
                //require_once static::$_directory . static::$_class . '.php';
                
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
                Exceptions::error_404(WEB . $module);
            }
            
            static::$_directory .= $module . '/';
            static::$_class = $module;
        }
        
        require_once static::$_directory . static::$_class . '.php';
        
        $class = ucfirst(static::$_class);
        $instance = new $class;
        static::_add_to_stack();
        
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
        return $instance;
    }
    
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
    
    private static function _add_to_stack()
    {
        static::$_modules[] = static::$_class;
    }
    
    private static function _remove_from_stack()
    {
        array_pop(static::$_modules);
    }
    
    public static function is_master()
    {
        return count(static::$_modules) === 1;
    }
}