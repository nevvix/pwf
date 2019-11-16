<?php

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
     * @param array $array
     * @return array
     */
    static public function array_merge($array) {
        global $config;

        // Overwrite cascading 'meta' key=>values pairs
        $meta = call_user_func_array('array_merge', array_column($array, 'meta'));

        // Append cascading 'link' key=>values pairs
        $link = call_user_func_array('array_merge_recursive', array_column($array, 'link'));

        // Prepend global links to environment links
        if ($environment = @$config->environment) {
            $all_env = array_remove($link, ['development', 'production']);
            $link[$environment] = array_merge($all_env, (array)@$head[$environment]);
            $link = array_select($link, ['development', 'production']);
        }
        else {
            $link = array_remove($link, ['development', 'production']);
        }

        // Append cascading 'script' key=>values pairs
        $script = call_user_func_array('array_merge_recursive', array_column($array, 'script'));

        // Prepend global scripts to environment scripts
        if ($head = @$script['head']) {
            if ($environment = @$config->environment) {
                $all_env = array_remove($head, ['development', 'production']);
                $head[$environment] = array_merge($all_env, (array)@$head[$environment]);
                $script['head'] = array_select($head, ['development', 'production']);
            }
            else {
                $script['head'] = array_remove($head, ['development', 'production']);
            }
        }
        if ($body = @$script['body']) {
            if ($environment = @$config->environment) {
                $all_env = array_remove($body, ['development', 'production']);
                $body[$environment] = array_merge($all_env, (array)@$body[$environment]);
                $script['body'] = array_select($body, ['development', 'production']);
            }
            else {
                $script['body'] = array_remove($body, ['development', 'production']);
            }
        }

        return compact('meta', 'link', 'script');
    }

    /**
     * Merge $config with meta.json files.
     */
    static public function merge_meta($config) {
        $json = JSON::merge(APP.'/json/meta.json', DIR.'/.meta.json');
        return (object)array_merge((array)$config, (array)$json);
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
