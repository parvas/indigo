<?php defined('SYSTEM') or exit('No direct script access allowed');

abstract class Model {
    
    public static function factory($model)
    {
       $class = ucfirst($model) . '_Model';
       
       require_once Module::find($model, 'model');
       
       return new $class;
    }
}

?>
