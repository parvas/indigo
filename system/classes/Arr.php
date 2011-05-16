<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Arr {
    
    public static function item(array $arr, $key = null, $value = null)
    {
        if (!is_null($key))
        {
            if (!is_null($value))
            {
                $arr[$key] = $value;
                return $arr;
            }
            
            return isset($arr[$key]) ? $arr[$key] : null;
        }
        
        return $arr;
    }
}