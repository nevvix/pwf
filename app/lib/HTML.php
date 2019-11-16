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
    const OPENGRAPH_KEY_PATTERN = '/^og\:/';

    /**
     * Create <meta> tags from json data.
     *
     * @param int $indent
     * @return string
     */
    static public function meta_tags($indent=0) {
        global $config;
        if ($meta = @$config->meta) {
            $meta = preg_grep_keys(self::OPENGRAPH_KEY_PATTERN, (array)$meta, PREG_GREP_INVERT);
            $charset = self::charset_tag($indent);
            $meta = array_remove($meta, ['charset', 'lang', 'title']);
            $array = [];
            foreach ($meta as $name => $content) {
        // echo '<pre>', print_r($meta,1), '</pre>';
        // echo '<h2>', @$meta->{$config->environment}, '</h2>';
        // exit;
                // $meta = @$meta->{$config->environment} ?: $meta;
                $array []= compact('name', 'content');
            }
            return $charset.self::tags('%s<meta %s>', $array, $indent);
            // return $charset.self::tags('%s<meta name="%s" content="%s">', $meta, $indent);
        }
    }

    /**
     * Create <meta> OpenGraph tags from json data.
     *
     * @param int $indent
     * @return string
     */
    static public function opengraph_tags($indent=0) {
        global $config;
        if ($meta = @$config->meta) {
            $opengraph = preg_grep_keys(self::OPENGRAPH_KEY_PATTERN, (array)$meta);
            $opengraph = @$opengraph->{@$config->environment} ?: $opengraph;
            $array = [];
            foreach ($meta as $property => $content) {
                $array []= compact('property', 'content');
            }
            return self::tags('%s<meta %s>', $meta, $indent);
            // return self::tags('%s<meta property="%s" content="%s">', $opengraph, $indent);
        }
    }

    /**
     * Create <meta> charset tag from json data.
     *
     * @param int $indent
     * @return string
     */
    static public function charset_tag($indent=0) {
        global $config;
        if ($charset = @$config->meta->charset->{@$config->environment} ?: @$config->meta->charset) {
            return self::tags('%s<meta %s>', compact('charset'), $indent);
            // return sprintf('%s<meta charset="%s">', $indent, $charset);
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
        if ($link = @$config->link->{@$config->environment} ?: @$config->link) {
            return self::tags('%s<link %s>', $link, $indent);
        }
    }

    /**
     * Create <script> tags from json data.
     *
     * @param string $section 'head' or 'body'
     * @param int $indent
     * @return string
     */
    static public function script_tags($section, $indent=0) {
        global $config;
        if ($script = @$config->script->{@$config->environment}->{$section} ?: @$config->script->{$section}) {
            return self::tags('%s<script %s></script>', $script, $indent);
        }
    }

    /**
     * Get an array of HTML tags.
     *
     * @param string $tag
     * @param mixed $object
     * @param int $indent
     * @return string
     */
    static public function tags($tag, $object, $indent=0) {
        $html = [];
        if (is_array($object)) {
            foreach ($object as $obj) {
                $html []= call_user_func(__METHOD__, $tag, $obj, $indent);
            }
        }
        else {
            $html []= self::tag($tag, $object, $indent);
        }
        return join(PHP_EOL, $html).PHP_EOL;
    }

    /**
     * Make a HTML tag.
     *
     * @param string $tag
     * @param mixed $object
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
     * @param mixed $object
     * @return string
     */
    static public function join_attributes($object) {
        $attributes = [];
        foreach ($object as $key => $value) {
            // echo '<h2>', $key, '</h2>';
            // echo '<h2>', $value, '</h2>';
            $attributes []= in_array($key, self::$html5_boolean_attributes) ? $key : sprintf('%s="%s"', $key, $value);
        }
        return join(" ", $attributes);
    }
}
