<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class URL {
	
    private static $_uri = '';
    private static $_fragments = array();
    
    public static function fetch_array()
    {
        static::_explode_fragments();
        return static::$_fragments;
    }
    
    public static function fetch()
    {
        return static::$_uri;
    }
    
    public static function fetch_module()
    {
        return implode('/', array_slice(static::fetch_array(), 1));
    }
	
    private static function _explode_fragments()
    {
    	// get rid of trailing slash
        static::$_uri = rtrim($_SERVER['REQUEST_URI'], '/');

        foreach (explode('/', static::$_uri) as $fragment) 
        {
            static::$_fragments[] = $fragment;
        }
    }
    
    public static function fragment($part)
    {
        return isset(static::$_fragments[$part]) ? static::$_fragments[$part] : null;
    }
    
    public static function fetch_full()
    {
        return WEB . substr($_SERVER['REQUEST_URI'], 1);
    }
    
    public static function redirect($url)
    {
        header("Location: {$url}");
    }
}
