<?php
namespace KC\Data;

use \Codeception\TestCase\WPTestCase;

/**
 * The DatabaseManagerTest class contains methods to test the DatabaseManager class
 */
class DatabaseManagerTest extends WPTestCase {

    /**
     * Test the getDatabaseStructure method
     */
    public function testGetDatabaseStructure() : void {
        $databaseManager = new DatabaseManager();
        $this->assertNotEquals(0, strlen($databaseManager->getDatabaseStructure()));
    }
}