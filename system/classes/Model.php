<?php defined('SYSTEM') or exit('No direct script access allowed');

abstract class Model {
    
    public static function factory($model)
    {
       $class = ucfirst($model) . '_Model';
       self::_find($model);

       return new $class;
    }
    
    private static function _find($model)
    {
        require APP . 'modules/pages/' . $model . '_model.php';
    }
}

?>
