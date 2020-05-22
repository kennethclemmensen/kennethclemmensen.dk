<?php
namespace KC\Security;

use \Codeception\TestCase\WPTestCase;

/**
 * The SecurityTest class contains methods to test the Security class
 */
class SecurityTest extends WPTestCase {

    /**
     * Test the escapeUrl method
     */
    public function testEscapeUrl() : void {
        $expected = 'https://kennethclemmensen.dk';
        $this->assertEquals($expected, Security::escapeUrl($expected));
    }

    /**
     * Test the hasApiAccess method
     */
    public function testHasApiAccess() : void {
        $expected = true;
        $this->assertEquals($expected, Security::hasApiAccess());
    }

    /**
     * Test the sanitizeString method
     */
    public function testSanitizeString() : void {
        $expected = '';
        $this->assertEquals($expected, Security::sanitizeString(''));
    }

    /**
     * Test the isValid method
     */
    public function testIsValid() : void {
        $expected = false;
        $this->assertEquals($expected, Security::isValid(''));
    }
}