<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Exceptions extends Exception {
    
    /**
     * Stores current ooutput buffering level.
     * 
     * @var int
     * @static
     */
    private static $_ob_level;
    
    /**
     * Stores all native PHP error levels.
     * 
     * @var array
     * @static 
     */
    private static $_levels = array (
                E_ERROR              => 'Error',
                E_WARNING            => 'Warning',
                E_PARSE              => 'Parsing Error',
                E_NOTICE             => 'Notice',
                E_CORE_ERROR         => 'Core Error',
                E_CORE_WARNING       => 'Core Warning',
                E_COMPILE_ERROR      => 'Compile Error',
                E_COMPILE_WARNING    => 'Compile Warning',
                E_USER_ERROR         => 'User Error',
                E_USER_WARNING       => 'User Warning',
                E_USER_NOTICE        => 'User Notice',
                E_STRICT             => 'Runtime Notice',
                E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
                );
    
    /**
     * Class constructor, extends parent.
     * 
     * @param string $message  Exception message text.
     * @param int $code        Exception code.
     */
    public function __construct($message, $code = 1)
    {
        parent::__construct($message, $code);
    }
    
    /**
     * Renders a 404 (Page Not Found) error.
     * 
     * @access public
     * @param  string $url  The URL that triggered the error. 
     * @uses   Log::write   Logs the error into a file.
     * @uses   Exceptions::_render_exception Outputs the exception to HTML template.
     * @static
     */
    public static function error_404($url)
    {
        Log::write('error', "404 Page Not Found --> {$url}");
        static::_render_exception(APP . 'views/404.php');
        exit(1);
    }
    
    /**
     * Parses a development exception.
     * Registered at bootstrap as native exception handler.
     * 
     * @access public
     * @param  Exception $e  Exception to be parsed.
     * @uses   Exceptions::_render_exception Outputs the exception to HTML template.
     * @static
     */
    public static function exception(Exception $e)
    {
        $type = static::$_levels[$e->getCode()];
        $file = $e->getFile(); 
        $line = $e->getLine();
        $message = $e->getMessage();
        $trace = '<pre>' . $e->getTraceAsString() . '</pre>';
        
        $info = array(
            'type'      => $type,
            'message'   => $message,
            'file'      => $file,
            'line'      => $line,
            'trace'     => $trace
        );
        
        ob_end_clean();
        
<<<<<<< HEAD
        static::_render_exception(SYSTEM . 'views/error.php');
=======
        static::_render_exception(SYSTEM . 'views/error.php', $info);
>>>>>>> origin/master
        exit(1);
    }
    
    /**
     * Renders a native PHP error.
     * Registered at bootstrap as native error handler.
     * 
     * @access public
     * @param  string $type     Exception type.
     * @param  string $message  Exce[tion message.
     * @param  string $file     File that triggered the error.
     * @param  int $line        File line that triggered the error.
     * @uses   Exceptions::_render_exception Outputs the exception to HTML template.
     * @static
     */
    public static function php_error($type, $message, $file, $line)
    {
        $type = static::$_levels[$type];

        if (static::$_ob_level > ob_get_level() + 1)
        {
            ob_end_flush();
        }
        
        $info = array(
            'type'      => $type,
            'message'   => $message,
            'file'      => $file,
            'line'      => $line
        );
        
        static::_render_exception(SYSTEM . 'views/php_error.php', $info);
    }
    
    /**
     * Outputs exception to predefined HTML template.
     * 
     * @access private
     * @param  string $template  Path to the HTML template to output exception.
     * @static
     */
    private static function _render_exception($template, array $info = null)
    {
        if (!is_null($info))
        {
            extract($info);
        }
        
        ob_start();
        
        require $template;
        
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $buffer;
    }
}