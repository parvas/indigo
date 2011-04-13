<?php defined('SYSTEM') or exit('No direct script access allowed');

class DB extends Mongo {
    
    private static $_params = array();
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
        return self::$_instance = $mongo->{static::$_params['database']};
    }
    
    public function __construct() 
    {
        require_once APP . 'config/database.php';
        self::$_params['database'] = $db['database'];
        parent::__construct("mongodb://{$db['username']}:{$db['password']}@{$db['hostname']}/{$db['database']}");
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
