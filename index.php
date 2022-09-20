<?php
ini_set('session.gc_maxlifetime', 172800);
ini_set('session.cookie_lifetime', 172800);

define('_START_MEMORY_', memory_get_usage());
define('_START_TIME_', microtime(1));

/**
 * The main path constants
 */
// DIR
define('_DIR_', '/' . (strpos($_SERVER['HTTP_HOST'], '.loc') ? '' : 'example/')); // NEED TO REPLACE! ex: '/example/'

// Path to the application folder
define('_BASEPATH_', rtrim($_SERVER['DOCUMENT_ROOT'], '/') . _DIR_);

// The name of THIS file
define('_SELF_', pathinfo(__FILE__, PATHINFO_BASENAME));

// Path to the front controller (this file in filesystem)
define('_FCPATH_', str_replace(_SELF_, '', __FILE__));

// Path to the application folder
define('_SYSDIR_', _BASEPATH_ . 'app/');

// Path to the styles folder
define('_SITEDIR_', _DIR_ . 'app/');

// URI
define('_URI_', mb_substr($_SERVER['REQUEST_URI'], mb_strlen(_DIR_) - 1));

/**
 * LOAD SYSTEM
 */

include_once(_SYSDIR_ . 'system/Config' . (strpos($_SERVER['HTTP_HOST'], '.loc') ? '_loc' : '') . '.php');

if ($_SERVER['SCRIPT_FILENAME'] == _BASEPATH_ . 'index.php') {
    include_once(_SYSDIR_ . 'system/Core.php');
    $core = new Core;
}

/* End of file */