<?php defined('SYSTEM') or exit('No direct script access allowed');

class Base {
    
    /**
     * Class instances buffer.
     * 
     * @var array $_classes
     * @staticvar 
     */
    private static $_classes = array();
    
    /**
     * Class names buffer.
     * 
     * @var array $_loaded 
     * @staticvar
     */
    private static $_loaded = array();
    
    /**
     * Returns an instance of the requested class.
     * 
     * @access  public
     * @param   string $class  Name of class to be created.
     * @return  object         
     * @static
     */
    public static function load($class, $alias = NULL)
    {
        if (isset(self::$_classes[$class]))
        {
            // class is already loaded, just return it
            return self::$_classes[$class];
        }
        else
        {
            // no alias?
            if (is_null($alias))
            {
                // then store by classname
                $alias = $class;
            }
            require_once SYSTEM . 'core/' . $class . '.php';
            // store the classname
            self::$_loaded[$alias] = $class;
            // create new instance, store it and return it
            return (self::$_classes[$class] = new $class);
        }
    }
}