<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Text {
    
    public static function limit_words($text, $limit, $end = '...')
    {
        if (!is_int($limit) || $limit < 1)
        {
            throw new Exceptions('Invalid word limit supplied');
        }
        
        if (strlen($text) <= $limit)
        {
            return $text;
        }
        
        return implode(' ', array_slice(explode(' ', $text), 0, $limit)) . $end;
    }
    
    public static function limit_chars($text, $limit, $end = '...')
    {
        if (!is_int($limit) || $limit < 1)
        {
            throw new Exceptions('Invalid character limit supplied');
        }
        
        if (strlen($text) <= $limit)
        {
            return $text;
        }
        
        return substr($text, 0, $limit - strlen($end));
    }
    
    /**
     * Extracts array name from string if supplied argument is in array format,
     * for example: string: select[] -> name: select.
     * 
     * @access  private
     * @param   string $name  String containing variable name.
     * @return  string        Array name if argument is array, the argument itself otherwise.  
     * @static
     */
    public static function extract_array_name($name)
    {
        return (($pos = strpos($name, '[')) !== false) ? substr($name, $pos) : $name;	
    }
}
