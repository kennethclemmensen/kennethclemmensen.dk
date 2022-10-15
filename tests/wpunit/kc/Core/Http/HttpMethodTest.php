<?php
namespace Tests\WPUnit\KC\Core\Http;

use KC\Core\Http\HttpMethod;
use \Codeception\TestCase\WPTestCase;

/**
 * The HttpMethodTest class contains methods to test the HttpMethod enum
 */
class HttpMethodTest extends WPTestCase {

	/**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/Http/HttpMethod.php';
    }

	/**
	 * Test the Get value
	 */
	public function testGetValue() : void {
		$this->assertEquals('GET', HttpMethod::Get->value);
	}

	/**
	 * Test the Put value
	 */
	public function testPutValue() : void {
		$this->assertEquals('PUT', HttpMethod::Put->value);
	}
}