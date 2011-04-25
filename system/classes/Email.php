<?php defined('SYSTEM') or exit('No direct script access allowed');

class Email {
    
    private $_to;
    private $_subject;
    private $_message;
    private static $_instance;
    
    /**
     *
     * @return Email 
     */
    public static function instance()
    {
        if (self::$_instance)
        {
            return self::$_instance;
        }
        
        return self::$_instance = new Email;
    }
    
    public function __construct() {}
    
    public function from()
    {
        return $this;
    }
    
    /**
     *
     * @param type $to
     * @return Email 
     */
    public function to($to)
    {
        $this->_to = $to;
        return $this;
    }
    
    public function cc()
    {
        return $this;
    }
    
    public function bcc()
    {
        return $this;
    }
    
    /**
     *
     * @param type $subject
     * @return Email 
     */
    public function subject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }
    
    /**
     *
     * @param type $message
     * @return Email 
     */
    public function message($message)
    {
        $this->_message = $message;
        return $this;
    }
    
    public function send()
    {
        if (!mail($this->_to, $this->_subject, $this->_message))
        {
            echo 'E-mail error.';
        }
    }
}

?>
