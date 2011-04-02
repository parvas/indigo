<?php defined('SYSTEM') or exit('No direct script access allowed');

class DB extends Mongo {
    
    private static $_instance;
    
    /**
     *
     * @return DB
     */
    public static function instance()
    {
        if (self::$_instance)
        {
            return self::$_instance;
        }
        
        $mongo = new DB();
        
        return self::$_instance = $mongo->{Config::get('database')};
    }
    
    public function __construct() 
    {
        parent::__construct();
    }
    
    /**
     *
     * @return MongoDate 
     */
    public static function date()
    {
        return new MongoDate();
    }
}

?>
