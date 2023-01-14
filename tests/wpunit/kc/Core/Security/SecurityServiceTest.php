<?php
namespace Tests\WPUnit\KC\Core\Security;

use KC\Core\Security\SecurityService;
use \Codeception\TestCase\WPTestCase;

/**
 * The SecurityServiceTest class contains methods to test the SecurityService class
 */
final class SecurityServiceTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/Security/SecurityService.php';
    }

    /**
     * Test the escapeUrl method
     */
    public function testEscapeUrl() : void {
        $expected = 'https://kennethclemmensen.dk';
        $this->assertEquals($expected, SecurityService::escapeUrl($expected));
    }

    /**
     * Test the hasApiAccess method
     */
    public function testHasApiAccess() : void {
        $expected = true;
        $this->assertEquals($expected, SecurityService::hasApiAccess());
    }

    /**
     * Test the sanitizeString method
     */
    public function testSanitizeString() : void {
        $expected = '';
        $this->assertEquals($expected, SecurityService::sanitizeString(''));
    }

    /**
     * Test the isValid method
     */
    public function testIsValid() : void {
        $expected = false;
        $this->assertEquals($expected, SecurityService::isValid(''));
    }

    /**
     * Test the encryptMessage method
     */
    public function testEncryptMessage() : void {
        $nonce = SecurityService::generateNonce();
        $key = SecurityService::generateEncryptionKey('Password');
        $this->assertNotEmpty(SecurityService::encryptMessage('message', $nonce, $key));
    }

    /**
     * Test the decryptMessage method
     */
    public function testDecryptMessage() : void {
        $nonce = SecurityService::generateNonce();
        $key = SecurityService::generateEncryptionKey('Password');
        $this->assertEmpty(SecurityService::decryptMessage('message', $nonce, $key));
    }

    /**
     * Test the generateEncryptionKey method
     */
    public function testGenerateEncryptionKey() : void {
        $this->assertNotEmpty(SecurityService::generateEncryptionKey('Password'));
    }

    /**
     * Test the generateNonce method
     */
    public function testGenerateNonce() : void {
        $this->assertNotEmpty(SecurityService::generateNonce());
    }
}