<?php defined('SYSTEM') or exit('No direct script access allowed');

function __autoload($class)
{
    require_once SYSTEM . "classes/{$class}.php";
}

Config::instance()->load('config');
I18n::set_lang('el');
Route::set();