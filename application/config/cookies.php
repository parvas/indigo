<?php defined('SYSTEM') or exit('No direct script access allowed');

$cookies = array(
    'path'       => '/',
    'expire'     => time() + 31536000, // one year
    'domain'     => '',
    'secure'     => false,
    'httponly'   => false
    );