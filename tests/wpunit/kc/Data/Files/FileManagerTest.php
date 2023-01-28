<?php
namespace Tests\WPUnit\KC\Data\Files;

use KC\Data\Files\FileManager;
use \Codeception\TestCase\WPTestCase;

/**
 * The FileManagerTest class contains methods to test the FileManager class
 */
final class FileManagerTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Data/Files/FileManager.php';
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