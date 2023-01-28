<?php
namespace Tests\WPUnit\KC\Core\Files;

use KC\Core\Files\FileService;
use \Codeception\TestCase\WPTestCase;

/**
 * The FileServiceTest class contains methods to test the FileService class
 */
class FileServiceTest extends WPTestCase {

	private FileService $fileService;
	private string $file;

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
		$this->fileService = new FileService();
		$this->file = '../../public/index.php';
        require_once '../../public/wp-content/plugins/kc/Core/Files/FileService.php';
    }

	/**
	 * Test the getFileContent method
	 */
	public function testGetFileContent() : void {
		$this->assertNotEquals('', $this->fileService->getFileContent($this->file));
	}

	/**
	 * Test the getFilesize method
	 */
	public function testGetFilesize() : void {
		$this->assertNotEquals('', $this->fileService->getFilesize($this->file));
	}
}