<?php defined('SYSTEM') or exit('No direct script access allowed');

class Cookie {
    
    /**
     * Set a cookie using native setcookie() PHP function.
     * 
     * @access public
     * @param  string $name    Cookie name. 
     * @param  string $value   Cookie value.
     * @param  int $expire     Cookie expiration in seconds.
     * @param  string $path    Cookie path.
     * @param  string $domain  Website domain.
     * @param  bool $secure    Cookie should only be set under an ssl connection.
     * @param  bool $httponly  Cookie accessible only through HTTP protocol.
     */
    public static function set($name, $value, $expire = null, $path = null, $domain = null, $secure = false, $httponly = false)
    {
        if (!isset($cookies))
        {
            // get cookie configuration items
            require_once APP . 'config/cookies.php';
        }
        
        if (!is_null($expire))
        {
            $expire += time();
        }
        else
        {
            $expire = $cookies['expire'];
        }
        
        if (is_null($path))
        {
            $path = $cookies['path'];
        }
        
        if (is_null($domain))
        {
            $domain = $cookies['domain'];
        }
        
        if ($secure !== true)
        {
            $secure = $cookies['secure'];
        }
        
        if ($httponly !== true)
        {
            $httponly = $cookies['httponly'];
        }
        
        if (!setcookie($name, $value, $expire, $path, $domain, $secure, $httponly))
        {
            Log::write('error', "Cookie '{$this->_name}' not set");
        }
    }
    
    /**
     * Returns a cookie.
     * 
     * @access public
     * @param  string $name  Name of the cookie requested. 
     * @return mixed   
     */
    public static function get($name)
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }
    
    /**
     * Delete cookie using native setcookie() PHP function.
     * 
     * @access public
     * @param  string $name    Cookie name. 
     * @param  string $path    Cookie path.
     * @param  string $domain  Website domain.
     * @param  bool $secure    Cookie should only be set under an ssl connection.
     * @param  bool $httponly  Cookie accessible only through HTTP protocol.
     * @uses   Cookie::set()
     */
    public static function delete($name, $path = null, $domain = null, $secure = false, $httponly = false)
    {
        static::set($name, '', -3600, $path, $domain, $secure, $httponly);
    }
}