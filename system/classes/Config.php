<?php defined('SYSTEM') or exit('No direct script access allowed');

class Config {
    
    public static function get($item)
    {
        require_once APP . 'config/config.php'; 
        return $config[$item];
    }
}