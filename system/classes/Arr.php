<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Arr {
    
    public static function get(array $arr, $key = null, $value = null)
    {
        if (!is_null($key))
        {
            if (!is_null($value))
            {
                $arr[$key] = $value;
                return $arr;
            }
            
            return $arr[$key];
        }
        
        return $arr;
    }
}