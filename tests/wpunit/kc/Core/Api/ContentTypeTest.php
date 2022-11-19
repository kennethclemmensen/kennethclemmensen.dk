<?php
namespace Tests\WPUnit\KC\Core\Api;

use KC\Core\Api\ContentType;
use \Codeception\TestCase\WPTestCase;

/**
 * The ContentTypeTest class contains methods to test the ContentType enum
 */
final class ContentTypeTest extends WPTestCase {

	/**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/Api/ContentType.php';
    }

	/**
	 * Test the FormUrlEncoded value
	 */
	public function testFormUrlEncodedValue() : void {
		$this->assertEquals('application/x-www-form-urlencoded', ContentType::FormUrlEncoded->value);
	}

	/**
	 * Test the OctetStream value
	 */
	public function testOctetStreamValue() : void {
		$this->assertEquals('application/octet-stream', ContentType::OctetStream->value);
	}
}