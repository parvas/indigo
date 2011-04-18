<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Log {
    
    /**
     * Stores log types.
     * 
     * @var array
     * @static 
     */
    private static $_types = array (
        'error' => 'ERROR',
        'debug' => 'DEBUG'
    );
    
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
    public static function write($type, $message)
    {
        $path = APP . 'logs/';
        
        if (!isset(static::$_types[$type]))
        {
            throw new Exceptions("Logging type '{$type}' not recognized");
        }
        
        if (!is_writeable($path) || empty($message))
        {
            throw new Exceptions("Directory '{$path}' is not writeable");
        }

        // separate file for specific date
        static::$_filename = 'log_' . date('Y-m-d') . '.php';
        
        if (!file_exists($path . static::$_filename))
        {
            static::$_message .= "<" . "?php if (!defined('SYSTEM')) exit('No direct script access allowed'); ?" . ">\n\n";
        }
        
        // create file if it does not exist
        if (!$fp = fopen($path . static::$_filename, 'a'))
        {
            throw new Exceptions('Error opening file ' . $path . static::$_filename . ' for editing');
        }
        
        static::$_message .= '[' . date('d-M-Y H:i:s') . '] ' . static::$_types[$type] . ' :: ' . $message . "\n";
        
        chmod($path . static::$_filename, 0633);
        flock($fp, LOCK_EX);
        fwrite($fp, static::$_message);
        flock($fp, LOCK_UN);
        chmod($path . static::$_filename, 0644);
        fclose($fp);
    }
}