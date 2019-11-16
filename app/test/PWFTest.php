<?php
require_once dirname(__DIR__).'/lib/PWF.php';

class HTMLElementTest extends PHPUnit\Framework\TestCase {

    public function test_array_merge() {
        $GLOBALS['config'] = JSON::read('config.json', ['assoc'=>TRUE]);
        $meta = JSON::read('meta.json', ['assoc'=>TRUE]);
        $_meta = JSON::read('.meta.json', ['assoc'=>TRUE]);

        $expected = JSON::read('expected.json', ['assoc'=>TRUE]);
        $actual = PWF::array_merge(compact('meta', '_meta'));

        $this->assertEquals($expected, $actual);
    }

}