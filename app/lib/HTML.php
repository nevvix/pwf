<?php

class HTML {

    // https://www.w3.org/TR/html51/single-page.html#attributes-table
    static private $html5_boolean_attributes = [
        'allowfullscreen',
        'async',
        'autofocus',
        'autoplay',
        'checked',
        'controls',
        'declare',
        'default',
        'defer',
        'disabled',
        'formnovalidate',
        'hidden',
        'ismap',
        'loop',
        'multiple',
        'muted',
        'novalidate',
        'open',
        'readonly',
        'required',
        'reversed',
        'selected',
        'typemustmatch',
    ];

    /**
     * Create <meta> OpenGraph tags from json data.
     *
     * @param int $indent
     * @return string
     */
    static public function ogp_tags($indent=0) {
        global $config;
        $html = [];
        if ($ogps = preg_grep_keys('/^og\:/', (array)@$config->meta)) {
            $indents = str_repeat(" ", (int)$indent);
            foreach ($ogps as $key => $value) {
                $html []= sprintf('%s<meta property="%s" content="%s">', $indents, $key, $value);
            }
        }
        return join(PHP_EOL, $html).PHP_EOL;
    }

    /**
     * Create <link> stylesheet tags from json data.
     *
     * @param int $indent
     * @return string
     */
    static public function css_tags($indent=0) {
        global $config;
        $html = [];
        if ($stylesheets = @$config->meta->stylesheets) {
            $indents = str_repeat(" ", (int)$indent);
            foreach ($stylesheets as $array) {
                $html []= sprintf('%s<link rel="stylesheet" %s>', $indents, self::join_attributes((array)$array));
            }
        }
        return join(PHP_EOL, $html).PHP_EOL;
    }

    /**
     * Create <script> src tags from json data.
     *
     * @param string $section 'head' or 'body'
     * @param int $indent
     * @return string
     */
    static public function js_tags($section, $indent=0) {
        global $config;
        $html = [];
        if ($scripts = @$config->meta->{$section."_scripts"}) {
            $indents = str_repeat(" ", (int)$indent);
            foreach ($scripts as $array) {
                $html []= sprintf('%s<script %s></script>', $indents, self::join_attributes((array)$array));
            }
        }
        return join(PHP_EOL, $html).PHP_EOL;
    }

    /**
     * Join array of HTML attributes.
     *
     * @param array $array
     * @return string
     */
    static public function join_attributes(array $array) {
        $attributes = [];
        foreach ($array as $key => $value) {
            $attributes []= in_array($key, self::$html5_boolean_attributes) ? $key : sprintf('%s="%s"', $key, $value);
        }
        return join(" ", $attributes);
    }
}
