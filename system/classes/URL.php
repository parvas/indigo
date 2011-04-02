<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class URL {
	
    private static $_uri = '';
    private static $_fragments = array();
    
    public static function fetch_array()
    {
        self::_explode_fragments();
        return self::$_fragments;
    }
    
    public static function fetch()
    {
        return self::$_uri;
    }
    
    public static function fetch_module()
    {
        return implode('/', array_slice(self::fetch_array(), 2));
    }
	
    private static function _explode_fragments()
    {
        self::$_uri = $_SERVER['REQUEST_URI'];
        
        foreach (explode('/', self::$_uri) as $fragment) 
        {
            self::$_fragments[] = $fragment;
        }
    }
    
    public function fragment($part)
    {
        return isset(self::$_fragments[$part]) ? self::$_fragments[$part] : NULL;
    }
}
