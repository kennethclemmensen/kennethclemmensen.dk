<?php
namespace Tests\WPUnit\KC\Core\Users;

use KC\Core\Users\UserRole;
use \Codeception\TestCase\WPTestCase;

/**
 * The UserRoleTest class contains methods to test the UserRole enum
 */
class UserRoleTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/Users/UserRole.php';
    }

	/**
	 * Test the Administrator value
	 */
	public function testAdministratorValue() : void {
		$this->assertEquals('administrator', UserRole::Administrator->value);
	}
}