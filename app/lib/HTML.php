<?php
require_once __DIR__.'/functions.php';

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
    const OPENGRAPH_KEY_PATTERN = '/^og\:/';

    /**
     * Create <meta> tags from json data.
     *
     * @param int $indent
     * @return string
     */
    static public function meta_tags($indent=0) {
        $html = array_filter([
            self::meta_charset_tag($indent),
            self::meta_name_tags($indent),
            self::opengraph_tags($indent),
        ]);
        return join(PHP_EOL, $html).PHP_EOL;
    }

    /**
     * Create <meta> tags from json data.
     *
     * @param int $indent
     * @return string
     */
    static public function meta_name_tags($indent=0) {
        global $config;
        return self::create_meta_tags('name', PREG_GREP_INVERT, $indent);
    }

    /**
     * Create <meta> OpenGraph tags from json data.
     *
     * @param int $indent
     * @return string
     */
    static public function opengraph_tags($indent=0) {
        global $config;
        return self::create_meta_tags('property', 0, $indent);
    }

    /**
     * Create 'name' or 'property' <meta> tags from json data.
     *
     * @param string $key
     * @param int $flags
     * @param int $indent
     * @return string
     */
    static private function create_meta_tags($key, $flags=0, $indent=0) {
        global $config;
        if ($meta = @$config->meta) {
            if ($meta = preg_grep_keys(self::OPENGRAPH_KEY_PATTERN, (array)$meta, $flags)) {
                $meta = array_remove($meta, ['charset', 'lang', 'title']);
                $html = [];
                foreach ($meta as $$key => $content) {
                    $content = @$content->{@$config->environment} ?: $content;
                    if (empty($config->environment) && is_object($content)) {
                        $content = reset($content);
                    }
                    $html []= self::tag('%s<meta %s>', compact($key, 'content'), $indent);
                }
                return join(PHP_EOL, $html);
            }
        }
    }

    /**
     * Create <meta> charset tag from json data.
     *
     * @param int $indent
     * @return string
     */
    static public function meta_charset_tag($indent=0) {
        global $config;
        if ($charset = @$config->meta->charset->{@$config->environment} ?: @$config->meta->charset) {
            if (empty($config->environment) && is_object($charset)) {
                $charset = reset($charset);
            }
            return self::tag('%s<meta %s>', compact('charset'), $indent);
        }
    }

    /**
     * Create <link> tags from json data.
     *
     * @param int $indent
     * @return string
     */
    static public function link_tags($indent=0) {
        global $config;
        if ($object = @$config->link) {
            return self::create_link_script_tags('%s<link %s>', $object, $indent);
        }
    }

    /**
     * Create <script> tags in <head> section from json data.
     *
     * @param int $indent
     * @return string
     */
    static public function head_script_tags($indent=0) {
        return self::script_tags('head', 4);
    }

    /**
     * Create <script> tags in <body> section from json data.
     *
     * @param int $indent
     * @return string
     */
    static public function body_script_tags($indent=0) {
        return self::script_tags('body', 4);
    }

    /**
     * Create <script> tags from json data.
     *
     * @param string $section 'head' or 'body'
     * @param int $indent
     * @return string
     */
    static private function script_tags($section, $indent=0) {
        global $config;
        if ($object = @$config->script->{$section}) {
            return self::create_link_script_tags('%s<script %s></script>', $object, $indent);
        }
    }

    /**
     * Create <link> or <script> tags from json data.
     *
     * @param string $tag
     * @param mixed $object stdClass or associative array
     * @param int $indent
     * @return string
     */
    static private function create_link_script_tags($tag, $object, $indent=0) {
        global $config;
            $object = @$object->{@$config->environment} ?: $object;
            $html = [];
            foreach ($object as $obj) {
                $html []= self::tag($tag, $obj, $indent);
            }
            return join(PHP_EOL, $html).PHP_EOL;
    }

    /**
     * Make a HTML tag.
     *
     * @param string $tag
     * @param mixed $object stdClass or associative array
     * @param int $indent
     * @return string
     */
    static public function tag($tag, $object, $indent=0) {
        $indent = str_repeat(" ", intval($indent));
        return sprintf($tag, $indent, self::join_attributes($object));
    }

    /**
     * Join array of HTML attributes.
     *
     * @param mixed $object stdClass or associative array
     * @return string
     */
    static public function join_attributes($object) {
        $attributes = [];
        foreach ($object as $key => $value) {
            $attributes []= in_array($key, self::$html5_boolean_attributes) ? $key : sprintf('%s="%s"', $key, $value);
        }
        return join(" ", $attributes);
    }
}
