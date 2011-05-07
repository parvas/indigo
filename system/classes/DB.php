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
        if (static::$_instance)
        {
            return static::$_instance;
        }
        
        $mongo = new DB();
        return static::$_instance = $mongo->{static::$_params['database']};
    }
    
    public function __construct() 
    {
        require_once APP . 'config/database.php';
        static::$_params['database'] = $db['database'];
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
    
    /**
     *
     * @return MongoId 
     */
    public static function id($id)
    {
        return new MongoId($id);
    }
    
    public static function as_array(MongoCursor $cursor)
    {
        $data = array();
        
        while ($cursor->hasNext())
        {
            $data[] = $cursor->getNext();
        }
        
        return $data;
    }
}