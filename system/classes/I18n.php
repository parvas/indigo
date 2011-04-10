<?php defined('SYSTEM') or exit('No direct script access allowed');

class I18n {
    
    /**
     * Stores current language/locale.
     * 
     * @access private
     * @var string
     */
    private $_lang;
    
    /**
     * Caches loaded filenames.
     * 
     * @access private
     * @var array 
     */
    private $_files = array();
    
    /**
     * Caches language phrases already loaded.
     * 
     * @access private
     * @var array 
     */
    private $_lines = array();
    
    /**
     * Class instance.
     * 
     * @access private
     * @var I18n 
     */
    private static $_instance;
    
    /**
     * Stores all enabled languages/locales.
     * 
     * @access private
     * @var array 
     */
    private $_locales = array();
    
    /**
     * Singleton method.
     * 
     * @access public
     * @return I18n
     */
    public static function instance()
    {
        if (static::$_instance)
        {
            return static::$_instance;
        }
        
        return static::$_instance = new I18n;
    }
    
    /**
     * Class constructor.
     * 
     * @access private
     */
    private function __construct()
    {
        $this->_init();
    }
    
    /**
     * Load enabled languages from main configuration file and determine
     * current language.
     * 
     * @access private
     */
    private function _init()
    {
        // get languages from locales config file
        require_once APP . 'config/locales.php';
        
        foreach ($locales as $locale => $params)
        {
            // disabled locales should not bother us
            if ($params['active'] === true)
            {
                // check for language switch
                if (isset($_POST['locale_' . $locale]))
                {
                    // language switched, set cookie to remember the selection
                    Cookie::set('locale', $locale);
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                }
                
                $this->_locales[$locale] = $params;
            }
        }
        
        // is a cookie set?
        if (isset($_COOKIE['locale']))
        {
            // language has been disabled or someone has messed up with cookies...
            if (isset($this->_locales[$_COOKIE['locale']]))
            {
                // load the language
                $this->_lang = $_COOKIE['locale'];
                return;
            }
            else
            {
                // cookie is either useless or suspicious
                Cookie::delete('locale');
            }
        }
            
        // user has not selected a language, load default
        $this->_lang = Config::instance()->get('default_language');
    }
    
    /**
     * Renders the language selection box.
     * 
     * @access private
     * @return string  HTML containing the locale box form.
     */
    public function locale_box()
    {
        $form = '<form method="post">' . "\n";
        
        foreach ($this->_locales as $locale => $params)
        {
            $form .= '<input type="submit" value="" name="locale_' . $locale . '" id="' . $locale . '" title="' . $params['language'] . '">' . "\n";
        }

        return $form . "</form>\n";
    }
    
    /**
     * Load requested language file. 
     * Main language file is loaded if no parameter passed.
     *  
     * @access public
     * @param  mixed $filename  Name of file to be loaded.
     */
    public function load($filename = null)
    {
        // Main language file requested
        if (is_null($filename))
        {
            // include file and exit
            require_once APP . 'languages/' . $this->_lang . '.php';
            return;
        }
        
        // if file is already loaded, do nothing
        if (!in_array($filename, $this->_files))
        {
            require_once LANGS . $this->_lang . '/' . $filename . '.php';
            
            $this->_files[] = $filename;
            $this->_lines = array_merge($this->_lines, $lang);
        }
    }
    
    /**
     * Returns a line of text from a loaded language file, 
     * empty string if index is not found.
     * 
     * @access public
     * @param  string $index  Array key of requested line.
     * @return string         Language line requested. 
     */
    public function line($index)
    {
        return isset($this->_lines[$index]) ? $this->_lines[$index] : '';
    }
    
    /**
     * Force set a language.
     * 
     * @param string $lang       Code of language to be loaded.
     * @param bool   $permanent  Whether the change should be made permanent.
     */
    public function set_lang($language, $permanent = false)
    {
        if (!isset($this->_locales[$language]))
        {
            echo "Error. Requsted language {$language} not found.";
            return;
        }
        
        $this->_lang = $language;
        
        if ($permanent === true)
        {
            // set a cookie to mark change as permanent
            Cookie::set('locale', $language);
        }
    }
    
    /**
     * Returns current language.
     * 
     * @return string  Current language code. 
     */
    public function current()
    {
        return $this->_lang;
    }
}