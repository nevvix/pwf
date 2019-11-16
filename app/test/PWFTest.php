<?php
if (!defined('APP')) {
    define('APP', dirname(__DIR__));
}
require_once APP.'/lib/PWF.php';

/**
 * From the test directory (app/test)
 * wget -O phpunit.phar https://phar.phpunit.de/phpunit-8.phar
 * php phpunit.phar .
 */
class PWFTest extends PHPUnit\Framework\TestCase {

    public function test_array_merge() {
        $GLOBALS['config'] = JSON::read(__DIR__.'/config.json', ['assoc'=>TRUE]);
        $meta = JSON::read(__DIR__.'/meta.json', ['assoc'=>TRUE]);
        $_meta = JSON::read(__DIR__.'/.meta.json', ['assoc'=>TRUE]);

        $expected = JSON::read(__DIR__.'/expected.json', ['assoc'=>TRUE]);
        $actual = PWF::array_merge(compact('meta', '_meta'));

        $this->assertEquals($expected, $actual);
    }

}