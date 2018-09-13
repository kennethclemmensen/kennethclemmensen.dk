<?php
class UnitTest extends \Codeception\Test\Unit {

    public function testMe() : void {
        $expected = 1;
        $actual = 1;
        $this->assertEquals($expected, $actual);
    }
}