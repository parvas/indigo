<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Log {
    
    /**
     * Stores log filename.
     * 
     * @var string
     * @static 
     */
    private static $_filename;
    
    /**
     * Stores log message.
     * 
     * @var string
     * @static 
     */
    private static $_message;
    
    /**
     * Logs a message in /application/logs/ directory.
     * Logs are stored in separate files for specific dates. 
     * 
     * @param string $message  The message to be logged.
     */
    public static function write($message)
    {
        $path = APP . 'logs/';
        
        if (!is_writeable($path) || empty($message))
        {
            return;
        }

        // separate file for specific date
        static::$_filename = 'log_' . date('Y-m-d') . '.php';
        
        if (!file_exists($path . static::$_filename))
        {
            static::$_message .= "<" . "?php if (!defined('SYSTEM')) exit('No direct script access allowed'); ?" . ">\n\n";
        }
        
        // create file of it does not exist
        if (!$fp = fopen($path . static::$_filename, 'a'))
        {
            return;
        }
        
        static::$_message .= '[' . date('d-M-Y H:i:s') . '] --> ' . $message . "\n";
        
        chmod($path . static::$_filename, 0633);
        flock($fp, LOCK_EX);
        fwrite($fp, static::$_message);
        flock($fp, LOCK_UN);
        chmod($path . static::$_filename, 0644);
        fclose($fp);
    }
}