<?php
namespace Tests\WPBrowser\Integration\Plugins\KC\Data\Database;

use KC\Data\Database\DatabaseManager;
use lucatume\WPBrowser\TestCase\WPTestCase;

/**
 * The DatabaseManagerTest class contains methods to test the DatabaseManager class
 */
final class DatabaseManagerTest extends WPTestCase {

    /**
     * The setUp method is called before each test
     */
    public function setUp() : void {
		require_once __DIR__.'/../../../../../../../public/wp-content/plugins/kc/Data/Database/DatabaseManager.php';
    }

    /**
     * Test the getDatabaseStructure method
     */
    public function testGetDatabaseStructure() : void {
        $databaseManager = new DatabaseManager();
        $this->assertNotEquals(0, strlen($databaseManager->getDatabaseStructure()));
    }
}