<?php
namespace Tests\WPUnit\KC\Data;

use KC\Data\FileManager;
use \Codeception\TestCase\WPTestCase;

/**
 * The FileManagerTest class contains methods to test the FileManager class
 */
class FileManagerTest extends WPTestCase {

    private FileManager $fileManager;

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Data/FileManager.php';
        $this->fileManager = new FileManager();
    }

	/**
	 * Test the getFiles method
	 */
	public function testGetFiles() : void {
		$expected = 3;
		$this->assertEquals($expected, count($this->fileManager->getFiles(__DIR__)));
	}
}