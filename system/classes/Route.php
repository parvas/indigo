<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Route {
    
    private static $_module;
    
    public static function set()
    {
        self::$_module = URL::fetch_module();
        
        if (self::$_module == '')
        {
            self::$_module = Config::instance()->get('default_controller');
        }
        
        Module::factory(self::$_module);
    }
}