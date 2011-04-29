<?php

/*
 * set error reporting
 */
 ini_set('display_errors', '1');
 error_reporting(E_ALL | ~E_NOTICE);

/*
 * Define all necessary paths
 * 
 * - SELF:      name of this THIS file (index.php)
 * - SERVER:    full server path to root folder
 * - WEB:       URL path to root folder
 * - APP:       full server path to application folder
 * - SYSTEM:    full server path to system folder
 * - JS:        full server path to javascript folder
 * - CSS:       full server path to css folder 
 * - IMAGES:    full server path to images folder 
 * - THIRD:     full server path to third party files folder 
 */
define ('SELF',     pathinfo(__FILE__, PATHINFO_BASENAME));
define ('SERVER',   str_replace(SELF, '', __FILE__));
define ('WEB',      'http://www.indigo.gr/');
define ('APP',      SERVER . 'application/');
define ('SYSTEM',   SERVER . 'system/');
define ('JS',       WEB . 'application/assets/js/');
define ('CSS',      WEB . 'application/assets/css/');
define ('IMAGES',   WEB . 'application/assets/images/');
define ('THIRD',    WEB . 'application/assets/third_party/');

// Come on, Indigo!!!
require_once SYSTEM . 'classes/Bootstrap.php';