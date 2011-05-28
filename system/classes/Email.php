<?php defined('SYSTEM') or exit('No direct script access allowed');

class Email {
    
    /**
     * @access private
     * @var string Stores recepient address 
     */
    private $_to;
    
    /**
     * @access private
     * @var string Stores message headers 
     */
    private $_headers;
    
    /**
     * @access private
     * @var string Stores message subject
     */
    private $_subject;
    
    /**
     * @access private
     * @var string Stores message body
     */
    private $_message;
    
    /**
     * @access private
     * @var Email Stores class instance 
     */
    private static $_instance;
    
    
    /**
     * Returns an instance of the class.
     * 
     * @access public
     * @return Email  Class instance.
     * @static 
     */
    public static function instance()
    {
        if (self::$_instance)
        {
            return self::$_instance;
        }
        
        return self::$_instance = new Email;
    }
    
    /**
     * Class constructor.
     * Initializes headers with mime and content type. 
     */
    public function __construct() 
    {
        $this->_headers = "MIME-Version: 1.0\r\n";
        $this->_headers .= "Content-type: text/html; charset=utf-8\r\n";
    }
    
    /**
     * Sets sender address.
     * 
     * @access private
     * @param  string $name   Verbose sender name.
     * @param  string $email  Sender email address.
     * @return Email          Class instance.
     */
    public function from($name, $email)
    {
        $this->_headers .= "From: {$name} <{$email}>\r\n";
        
        return $this;
    }
    
    /**
     * Adds message recipient(s).
     * 
     * @access public
     * @param  string|array $to  Recepient address(es).
     * @return Email             Class instance.
     */
    public function to($to)
    {
        if (is_array($to))
        {
            $to = implode(',', $to);
        }
        
        $this->_to = $to;
        return $this;
    }
    
    /**
     * Appends carbon copy to message headers.
     * 
     * @access public
     * @param  string|array $cc  Cc address(es).
     * @return Email             Class instance.
     */
    public function cc($cc)
    {
        if (is_array($cc))
        {
            $cc = implode(',', $cc);
        }
        
        $this->_headers .= "Cc: $cc\r\n";
        return $this;
    }
    
    /**
     * Appends blind carbon copy to message headers.
     * 
     * @access public
     * @param  string|array $bcc  Bcc address(es).
     * @return Email              Class instance.
     */
    public function bcc($bcc)
    {
        if (is_array($bcc))
        {
            $bcc = implode(',', $bcc);
        }
        
        $this->_headers .= "Bcc: $bcc\r\n";
        return $this;
    }
    
    /**
     * Appends email subject.
     * 
     * @access public
     * @param string $subject  Message subject.
     * @return Email           Class instance.
     */
    public function subject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }
    
    /**
     * Appends email body.
     * 
     * @access public
     * @param  string $message  Message body. 
     * @return Email            Class instance.
     */
    public function message($message)
    {
        $this->_message = $message;
        return $this;
    }
    
    /**
     * Sends the email using native PHP mail() function.
     * 
     * @access public
     * @return boolean  True if message succesfully sent, false otherwise. 
     */
    public function send()
    {
        if (!mail($this->_to, $this->_subject, $this->_message, $this->_headers))
        {
            Log::write('error', 'Problem occured while sending email');
            return false;
        }
        
        return true;
    }
}