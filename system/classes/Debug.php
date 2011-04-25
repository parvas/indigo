<?php  if (!defined('SYSTEM')) exit('No direct script access allowed');

class Debug {
    	
    public static function pre_print($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }
    
    public static function pre_dump($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
    
    public static function alert($text)
    {
        echo '<script>alert("' . $text . '")</script>';
    }
    
    public static function post()
    {
        static::pre_print($_POST);
    }
    
    public static function session()
    {
        static::pre_print($_SESSION);
    }
    
    public static function cookies()
    {
        static::pre_print($_COOKIE);
    }
    
    public static function dump_post()
    {
        static::pre_dump($_POST);
    }
    
    public static function dump_session()
    {
        static::pre_dump($_SESSION);
    }
    
    public static function dump_cookies()
    {
        static::pre_dump($_COOKIE);
    }
}
