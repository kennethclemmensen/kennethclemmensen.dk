<?php
namespace Tests\WPUnit\KC\Core\Api;

use KC\Core\Api\HttpHeader;
use \Codeception\TestCase\WPTestCase;

/**
 * The HttpHeaderTest class contains methods to test the HttpHeader enum
 */
final class HttpHeaderTest extends WPTestCase {

	/**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/Api/HttpHeader.php';
    }

	/**
	 * Test the AccessControlAllowOrigin value
	 */
	public function testAccessControlAllowOriginValue() : void {
		$this->assertEquals('Access-Control-Allow-Origin', HttpHeader::AccessControlAllowOrigin->value);
	}

	/**
	 * Test the CacheControl value
	 */
	public function testCacheControlValue() : void {
		$this->assertEquals('Cache-Control', HttpHeader::CacheControl->value);
	}

	/**
	 * Test the ContentDescription value
	 */
	public function testContentDescriptionValue() : void {
		$this->assertEquals('Content-Description', HttpHeader::ContentDescription->value);
	}

	/**
	 * Test the ContentDisposition value
	 */
	public function testContentDispositionValue() : void {
		$this->assertEquals('Content-Disposition', HttpHeader::ContentDisposition->value);
	}

	/**
	 * Test the ContentLength value
	 */
	public function testContentLengthValue() : void {
		$this->assertEquals('Content-Length', HttpHeader::ContentLength->value);
	}

	/**
	 * Test the ContentType value
	 */
	public function testContentTypeValue() : void {
		$this->assertEquals('Content-Type', HttpHeader::ContentType->value);
	}

	/**
	 * Test the Expires value
	 */
	public function testExpiresValue() : void {
		$this->assertEquals('Expires', HttpHeader::Expires->value);
	}

	/**
	 * Test the Pragma value
	 */
	public function testPragmaValue() : void {
		$this->assertEquals('Pragma', HttpHeader::Pragma->value);
	}
}