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
class PWFArrayMergeTest extends PHPUnit\Framework\TestCase {

    public function test_01() {
        $GLOBALS['config'] = ['environment'=>'development'];
        $expected = $this->expected_file('01');
        $actual = PWF::array_merge($this->actual_files('01'));
        $this->assertEquals($expected, $actual);
    }

    private function actual_files($dir) {
        return [
            $this->json_decode($dir.'/meta.json'),
            $this->json_decode($dir.'/.meta.json'),
        ];
    }

    private function expected_file($dir) {
        return $this->json_decode($dir.'/expected.json');
    }

    private function json_decode($filename) {
        $filename = sprintf('%s/%s/%s', __DIR__, $GLOBALS['config']['environment'], $filename);
        return json_decode(file_get_contents($filename), TRUE);
    }
}