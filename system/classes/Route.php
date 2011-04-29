<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Route {
    
    private static $_module;
    
    public static function set()
    {
        static::$_module = URL::fetch_module();
        
        if (static::$_module == '')
        {
            static::$_module = Config::instance()->get('default_controller');
            header('Location: ' . WEB . static::$_module);
        }

        Module::factory(static::$_module);
    }
}