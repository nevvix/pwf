<?php
define('ROOT', dirname(__DIR__));
define('APP', ROOT.'/app');
define('WWW', ROOT.'/www');
define('VENDOR', ROOT.'/vendor');

// Libraries
require_once APP.'/lib/functions.php';
require_once APP.'/lib/JSON.php';
require_once APP.'/lib/PWF.php';
require_once APP.'/lib/HTML.php';
require_once VENDOR.'/parsedown/Parsedown.php';
require_once VENDOR.'/parsedown-extra/ParsedownExtra.php';

// Global $config object
$config = JSON::read(APP.'/json/config.json');

// PHP error log write permission
PWF::php_error_log();

// Select .htaccess
PWF::htaccess();

// Merge meta data into $config
PWF::merge_meta();
set_timezone(@$config->timezone);

// ini_set('xdebug.var_display_max_depth', '10');
// ini_set('xdebug.var_display_max_children', '256');
// ini_set('xdebug.var_display_max_data', '1024');
// var_dump($config,1);
echo '<pre>', print_r($config,1), '</pre>';
