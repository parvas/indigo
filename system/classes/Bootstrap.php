<?php defined('SYSTEM') or exit('No direct script access allowed');

function __autoload($class)
{
    require_once SYSTEM . "classes/{$class}.php";
}

//echo Config::get('default_language');
I18n::set_lang('el');
Route::set();