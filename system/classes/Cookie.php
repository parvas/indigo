<?php defined('SYSTEM') or exit('No direct script access allowed');

class Cookie {
    
    public static function set($name, $value, $expire = null)
    {
        if (!isset($cookies))
        {
            require_once APP . 'config/cookies.php';
        }
        
        if (!is_null($expire))
        {
            $cookies['expire'] = $expire + time();
        }
        
        return setcookie($name, $value, $cookies['expire'], 
                                        $cookies['path'], 
                                        $cookies['domain'], 
                                        $cookies['secure'], 
                                        $cookies['httponly']);
    }
    
    public static function get($name)
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }
    
    public static function delete($name)
    {
        return setcookie($name, '', time());
    }
}