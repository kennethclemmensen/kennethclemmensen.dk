<?php
class UnitTest extends \Codeception\Test\Unit {

    public function testMe() {
        $expected = 1;
        $actual = 1;
        $this->assertEquals($expected, $actual);
    }
}