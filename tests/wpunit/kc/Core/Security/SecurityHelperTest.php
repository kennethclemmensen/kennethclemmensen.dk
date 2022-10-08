<?php
namespace Tests\WPUnit\KC\Core\Security;

use KC\Core\Security\SecurityHelper;
use \Codeception\TestCase\WPTestCase;

/**
 * The SecurityHelperTest class contains methods to test the SecurityHelper class
 */
class SecurityHelperTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() {
        require_once '../../public/wp-content/plugins/kc/Core/Security/SecurityHelper.php';
    }

    /**
     * Test the escapeUrl method
     */
    public function testEscapeUrl() : void {
        $expected = 'https://kennethclemmensen.dk';
        $this->assertEquals($expected, SecurityHelper::escapeUrl($expected));
    }

    /**
     * Test the hasApiAccess method
     */
    public function testHasApiAccess() : void {
        $expected = true;
        $this->assertEquals($expected, SecurityHelper::hasApiAccess());
    }

    /**
     * Test the sanitizeString method
     */
    public function testSanitizeString() : void {
        $expected = '';
        $this->assertEquals($expected, SecurityHelper::sanitizeString(''));
    }

    /**
     * Test the isValid method
     */
    public function testIsValid() : void {
        $expected = false;
        $this->assertEquals($expected, SecurityHelper::isValid(''));
    }
}