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
        $GLOBALS['config'] = ['environment'=>'development'];
        $array = [
            $this->json_decode('meta.json'),
            $this->json_decode('.meta.json'),
        ];
        $expected = $this->json_decode('expected.json');
        $actual = PWF::array_merge($array);
        $this->assertEquals($expected, $actual);
    }

    private function json_decode($filename) {
        return json_decode(file_get_contents(__DIR__.'/'.$filename), TRUE);
    }
}