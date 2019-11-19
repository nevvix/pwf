<?php
if (!defined('APP')) {
    define('APP', dirname(__DIR__));
}
require_once __DIR__.'/functions.php';
require_once __DIR__.'/JSON.php';

class PWF {

    /**
     * Include a filename or its default parent.
     *
     * 1. Include if the file exists in the current directory
     * 2. Include if the file exists in the provided default directory ($kwargs['path'])
     * 3. Include if the file exists in the default directory
     * Speed-optimized function for the earliest return.
     * @param string $filename
     * @param array $kwargs
     */
    static public function load($filename, array $kwargs=[]) {
        global $config;
        $kwargs += [
            'path' => path(APP, '/html'), // Template path
        ];
        if (!file_exists($_path = $filename)) {
            if (!file_exists($_path = path($kwargs['path'], $filename))) {
                return FALSE;
            }
        }
        unset($filename, $kwargs);
        include $_path;
    }

    /**
     * Merge meta data arrays.
     *
     * @param array $assoc
     * @return array
     */
    static public function array_merge($assoc) {

        // Overwrite cascading 'meta' key=>value pairs
        $meta = call_user_func_array('array_merge', array_column($assoc, 'meta'));

        // Append cascading 'link' key=>value pairs
        $link = call_user_func_array('array_merge_recursive', array_column($assoc, 'link'));

        // Prepend global links to environment links
        $link = self::prepend_env($link);

        // Append cascading 'script' key=>value pairs
        $script = call_user_func_array('array_merge_recursive', array_column($assoc, 'script'));

        // Prepend global scripts to environment scripts
        if ($head = @$script['head']) {
            $script['head'] = self::prepend_env($head);
        }
        if ($body = @$script['body']) {
            $script['body'] = self::prepend_env($body);
        }

        return compact('meta', 'link', 'script');
    }

    /**
     * Prepend global data to environment data.
     *
     * @param array $assoc
     * @return array
     */
    static private function prepend_env($assoc) {
        global $config;
        $all_env = array_remove($assoc, ['development', 'production']);
        if ($environment = @$config->environment) {
            $assoc[$environment] = array_merge($all_env, (array)@$assoc[$environment]);
            return array_select($assoc, ['development', 'production']);
        }
        return $all_env;
    }

    /**
     * Convert Markdown to HTML.
     *
     * @param string $filename
     * @return string
     */
    static public function markdown_to_html($filename) {
        if (file_exists($filename)) {
            $text = file_get_contents($filename);
            return \Michelf\MarkdownExtra::defaultTransform($text);
        }
        http_response_code(302);
        $html = file_get_contents(path(APP, '/html/md_missing.html'));
        $filename = explode(WWW, $filename)[1];
        return format($html, compact('filename'));
    }

    /**
     * Merge $config with meta.json files.
     */
    static public function merge_meta() {
        global $config;
        $json = JSON::merge(
            path(APP, '/json/meta.json'),
            path(DIR, '.meta.json')
        );
        $config = (object)array_merge((array)$config, (array)$json);
    }

    /**
     * Select .htaccess from files in app/conf.
     */
    static public function htaccess() {
        global $config;
        if ($conf_htaccess = @$config->htaccess) {
            $conf_htaccess = path(ROOT, $conf_htaccess);
            $root_htaccess = path(ROOT, '.htaccess');
            if (sha1_file($root_htaccess) !== sha1_file($conf_htaccess)) {
                copy($conf_htaccess, $root_htaccess);
            }
        }
    }

    /**
     * Set PHP error log write permission.
     */
    static public function php_errors_log() {
        global $config;
        if ($php_errors_log = @$config->php_errors_log) {
            chmod(path(ROOT, $php_errors_log), 0664);
        }
    }
}
