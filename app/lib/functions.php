<?php

/**
 * Return a formatted string like vsprintf() with named placeholders.
 *
 * When a placeholder doesn't have a matching key in `$args`,
 *   the placeholder is returned as is to see missing args.
 * @param string $string
 * @param array $kwargs
 * @param string $pattern
 * @return string
 */
function format($string, array $kwargs, $pattern='/\{\{(\w+)\}\}/') {
    return preg_replace_callback($pattern, function ($matches) use ($kwargs) {
        return @$kwargs[$matches[1]] ?: $matches[0];
    }, $string);
}

/**
 * Normalize a path.
 *
 * Usage: path('./one/', '/two/', 'three/');
 * Result: "./one/two/three"
 * @param string $parts
 * @return string
 */
function path(...$parts) {
    $parts = array_filter($parts);
    $first = rtrim(array_shift($parts), DIRECTORY_SEPARATOR);
    $parts = array_map(function ($value) {
        return trim($value, DIRECTORY_SEPARATOR);
    }, $parts);
    array_unshift($parts, $first);
    return join(DIRECTORY_SEPARATOR, $parts);
}

/**
 * Set time zone in PHP.
 *
 * @param string $timezone
 */
function set_timezone($timezone) {
    if (empty(ini_get('date.timezone'))) {
        ini_set('date.timezone', $timezone);
    }
}

/**
 * Get array entries that match pattern in their keys.
 *
 * @link https://www.php.net/manual/en/function.preg-grep.php
 * @param string $pattern
 * @param array $assoc
 * @param int $flags
 * @return array
 */
function preg_grep_keys($pattern, array $assoc, $flags=0) {
    $grep = preg_grep((string)$pattern, array_keys($assoc), (int)$flags);
    return array_select($assoc, $grep);
}

/**
 * Select a subset of an associative array by providing the keys.
 *
 * @param array $assoc
 * @param array $keys
 * @return array
 */
function array_select(array $assoc, array $keys) {
    return array_intersect_key($assoc, array_flip($keys));
}

/**
 * Remove a subset of an associative array by providing the keys.
 *
 * @param array $assoc
 * @param array $keys
 * @return array
 */
function array_remove(array $assoc, array $keys) {
    return array_diff_key($assoc, array_flip($keys));
}
