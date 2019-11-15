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
            'path' => APP.'/html', // Template path
        ];
        if (!file_exists($_path = $filename)) {
            if (!file_exists($_path = path($kwargs['path'], $filename))) {
                return FALSE;
            }
        }
        unset($filename, $kwargs);
        include $_path;
    }

    static public function markdown_to_html($filename) {
        if (file_exists($filename)) {
            $text = file_get_contents($filename);
            return \Michelf\MarkdownExtra::defaultTransform($text);
        }
        http_response_code(302);
        $html = file_get_contents(APP.'/html/md_missing.html');
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
