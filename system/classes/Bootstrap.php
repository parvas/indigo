<?php defined('SYSTEM') or exit('No direct script access allowed');

function __autoload($class)
{
    require_once SYSTEM . "classes/{$class}.php";
}

// load main configuration file
Config::instance()->load('config');

// load main language file
require_once APP . 'languages/' . I18n::instance()->current() . '.php';

// start routing
Route::set();