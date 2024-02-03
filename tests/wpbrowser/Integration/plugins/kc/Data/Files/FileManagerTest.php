<?php
namespace Tests\WPBrowser\Integration\Plugins\KC\Data\Files;

use KC\Data\Files\FileManager;
use lucatume\WPBrowser\TestCase\WPTestCase;

/**
 * The FileManagerTest class contains methods to test the FileManager class
 */
final class FileManagerTest extends WPTestCase {

    /**
     * The setUp method is called before each test
     */
    public function setUp() : void {
		require_once __DIR__.'/../../../../../../../public/wp-content/plugins/kc/Data/Files/FileManager.php';
    }

	/**
	 * Test the getFiles method
	 */
	public function testGetFiles() : void {
        $fileManager = new FileManager();
		$expected = 1;
		$this->assertEquals($expected, count($fileManager->getFiles(__DIR__)));
	}
}