<?php defined('SYSTEM') or exit('No direct script access allowed');

class I18n {
	
    private static $_lang;
    
    public static function load($filename = null)
    {
        if (is_null($filename))
        {
            require_once APP . 'languages/' . self::$_lang . '.php';
            return;
        }
        
        require LANGS . self::$_lang . '/' . $filename . '.php';

        if (!isset($lang))
        {
            echo "Language file '{$filename}.php' not found.";
            return;
        }
        
        return $lang;
    }
    
    public static function set_lang($lang)
    {
        self::$_lang = $lang;
    }
    
    public static function current()
    {
        return self::$_lang;
    }
    
    public function set()
    {
    }
}
