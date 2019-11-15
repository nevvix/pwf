<?php
define('ROOT', __DIR__);
define('APP', ROOT.'/app');
define('WWW', ROOT.'/www');

// Global config object
$config = json_decode(file_get_contents(APP.'/json/config.json'));

// Libraries
require_once APP.'/lib/functions.php';
require_once APP.'/lib/PWF.php';
require_once APP.'/lib/HTML.php';
require_once APP.'/lib/JSON.php';
require_once APP.'/vendor/php-markdown/Michelf/MarkdownExtra.inc.php';

// PHP error log write permission
PWF::php_errors_log();

// Select .htaccess
PWF::htaccess();

// Meta data
$config->meta = JSON::merge(
    APP.'/json/meta.json',
    APP.'/json/ogp.json',
    DIR.'/.meta.json',
    DIR.'/.ogp.json'
);
set_timezone(@$config->timezone);

// Load current page
PWF::load('html.php');
