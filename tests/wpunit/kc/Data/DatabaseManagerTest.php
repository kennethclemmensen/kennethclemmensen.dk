<?php
namespace Tests\WPUnit\KC\Data;

use KC\Data\DatabaseManager;
use \Codeception\TestCase\WPTestCase;

/**
 * The DatabaseManagerTest class contains methods to test the DatabaseManager class
 */
final class DatabaseManagerTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Data/DatabaseManager.php';
    }

    /**
     * Test the getDatabaseStructure method
     */
    public function testGetDatabaseStructure() : void {
        $databaseManager = new DatabaseManager();
        $this->assertNotEquals(0, strlen($databaseManager->getDatabaseStructure()));
    }
}