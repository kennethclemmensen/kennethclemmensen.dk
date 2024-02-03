<?php
namespace Tests\WPBrowser\Integration\Plugins\KC\Core\Security;

use KC\Core\Security\SecurityService;
use lucatume\WPBrowser\TestCase\WPTestCase;

/**
 * The SecurityServiceTest class contains methods to test the SecurityService class
 */
final class SecurityServiceTest extends WPTestCase {

    private SecurityService $securityService;

    /**
     * The setUp method is called before each test
     */
    public function setUp() : void {
		require_once __DIR__.'/../../../../../../../public/wp-content/plugins/kc/Core/Security/SecurityService.php';
		$this->securityService = new SecurityService();
    }

    /**
     * Test the escapeUrl method
     */
    public function testEscapeUrl() : void {
        $expected = 'https://kennethclemmensen.dk';
        $this->assertEquals($expected, $this->securityService->escapeUrl($expected));
    }

    /**
     * Test the hasApiAccess method
     */
    public function testHasApiAccess() : void {
        $expected = true;
        $this->assertEquals($expected, $this->securityService->hasApiAccess());
    }

    /**
     * Test the sanitizeString method
     */
    public function testSanitizeString() : void {
        $expected = '';
        $this->assertEquals($expected, $this->securityService->sanitizeString(''));
    }

    /**
     * Test the isValid method
     */
    public function testIsValid() : void {
        $expected = false;
        $this->assertEquals($expected, $this->securityService->isValid(''));
    }

    /**
     * Test the encryptMessage method
     */
    public function testEncryptMessage() : void {
        $nonce = $this->securityService->generateNonce();
        $key = $this->securityService->generateEncryptionKey('Password');
        $this->assertNotEmpty($this->securityService->encryptMessage('message', $nonce, $key));
    }

    /**
     * Test the decryptMessage method
     */
    public function testDecryptMessage() : void {
        $nonce = $this->securityService->generateNonce();
        $key = $this->securityService->generateEncryptionKey('Password');
        $this->assertEmpty($this->securityService->decryptMessage('message', $nonce, $key));
    }

    /**
     * Test the generateEncryptionKey method
     */
    public function testGenerateEncryptionKey() : void {
        $this->assertNotEmpty($this->securityService->generateEncryptionKey('Password'));
    }

    /**
     * Test the generateNonce method
     */
    public function testGenerateNonce() : void {
        $this->assertNotEmpty($this->securityService->generateNonce());
    }
}