<?php

class JSON {

    const JSON_DECODE_ASSOC = FALSE; // TRUE= associative array, FALSE= stdClass object
    const JSON_DECODE_DEPTH = 512;
    const JSON_DECODE_OPTIONS = 0;
    const JSON_ENCODE_OPTIONS = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
    const JSON_ENCODE_DEPTH = 512;

    /**
     * Merge JSON files and return an associative array or a stdClass object.
     *
     * @link http://php.net/manual/en/function.call-user-func-array.php
     * @link http://php.net/manual/en/function.array-merge.php
     * @param array $filenames
     * @return array|object
     */
    static public function merge(...$filenames) {
        $param_arr = [];
        foreach ($filenames as $filename) {
            if (file_exists($filename)) {
                $kwargs = ['assoc'=>TRUE] + compact('filename');
                $param_arr []= self::read($filename, $kwargs);
            }
        }
        $array = PWF::array_merge($param_arr);
        $json = self::encode($array, ['options'=>self::JSON_ENCODE_OPTIONS]);
        return self::decode($json, ['assoc'=>self::JSON_DECODE_ASSOC]);
    }

    /**
     * Read a JSON file and return an associative array or object.
     *
     * @param string $filename
     * @param array $kwargs
     * @return array|object
     */
    static public function read($filename, array $kwargs=[]) {
        $json = @file_get_contents($filename);
        $kwargs += compact('filename');
        return self::decode($json, $kwargs);
    }

    /**
     * Write a JSON file from a value.
     *
     * @param string $filename
     * @param mixed $value
     * @param array $kwargs
     * @return boolean
     */
    static public function write($filename, $value, array $kwargs=[]) {
        $kwargs += compact('filename');
        $json = self::encode($value, $kwargs);
        return (bool)file_put_contents($filename, $json);
    }

    /**
     * Parse a JSON string into an array or object.
     *
     * @link http://php.net/manual/en/json.constants.php
     * @link http://php.net/manual/en/function.file-get-contents.php
     * @link http://php.net/manual/en/function.json-decode.php
     * @param string $json
     * @param array $kwargs
     * @return mixed
     */
    static public function decode($json, array $kwargs=[]) {
        extract($kwargs += [
            'assoc'    => self::JSON_DECODE_ASSOC,
            'depth'    => self::JSON_DECODE_DEPTH,
            'options'  => self::JSON_DECODE_OPTIONS,
            'filename' => NULL,
        ]);
        $value = json_decode($json, $assoc, $depth, $options);
        self::check_json_error($filename);
        return $value;
    }

    /**
     * Get the JSON representation of a value.
     *
     * @link http://php.net/manual/en/json.constants.php
     * @link http://php.net/manual/en/function.json-encode.php
     * @link http://php.net/manual/en/function.file-put-contents.php
     * @param mixed $value
     * @param array $kwargs
     * @return string
     */
    static public function encode($value, array $kwargs=[]) {
        extract($kwargs += [
            'options'  => self::JSON_ENCODE_OPTIONS,
            'depth'    => self::JSON_ENCODE_DEPTH,
            'filename' => NULL,
        ]);
        $json = json_encode($value, $options, $depth);
        self::check_json_error($filename);
        return $json;
    }

    /**
     * Throw RuntimeException of last JSON error.
     *
     * @param string $filename
     */
    static private function check_json_error($filename=NULL) {
        if (json_last_error() !== JSON_ERROR_NONE) {
            $message = empty($filename) ? "{ %s }" : "{ %s in '%s' }";
            throw new RuntimeException(sprintf($message, json_last_error_msg(), $filename));
        }
    }
}
