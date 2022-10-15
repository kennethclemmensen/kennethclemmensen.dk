<?php
namespace Tests\WPUnit\KC\Core\PostTypes;

use KC\Core\PostTypes\SortingOrder;
use \Codeception\TestCase\WPTestCase;

/**
 * The SortingOrderTest class contains methods to test the SortingOrder enum
 */
class SortingOrderTest extends WPTestCase {

	/**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/PostTypes/SortingOrder.php';
    }

	/**
	 * Test the Ascending value
	 */
	public function testAscendingValue() : void {
		$this->assertEquals('ASC', SortingOrder::Ascending->value);
	}
}