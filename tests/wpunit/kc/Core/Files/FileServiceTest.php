<?php
namespace Tests\WPUnit\KC\Core\Files;

use KC\Core\Files\FileService;
use \Codeception\TestCase\WPTestCase;

/**
 * The FileServiceTest class contains methods to test the FileService class
 */
class FileServiceTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/Files/FileService.php';
    }

	/**
	 * Test the getFileContent method
	 */
	public function testGetFileContent() : void {
		$fileService = new FileService();
		$file = '../../public/index.php';
		$this->assertNotEquals('', $fileService->getFileContent($file));
	}
}