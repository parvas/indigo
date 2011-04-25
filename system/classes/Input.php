<?php defined('SYSTEM') or exit('No direct script access allowed');

class Input {   
    
    public static function post($item = null)
    {
        if (!is_null($item))
        {
            return isset($_POST[$item]) ? $_POST[$item] : null;
        }
        
        return $_POST;
    }
    
    public static function get($item = null)
    {
        if (!is_null($item))
        {
            return isset($_GET[$item]) ? $_GET[$item] : null;
        }
        
        return $_GET;
    }
}