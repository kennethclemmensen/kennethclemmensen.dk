<?php
namespace Tests\WPBrowser\Integration\Plugins\KC\Core\Files;

use KC\Core\Files\FileService;
use lucatume\WPBrowser\TestCase\WPTestCase;

/**
 * The FileServiceTest class contains methods to test the FileService class
 */
final class FileServiceTest extends WPTestCase {

	private FileService $fileService;
	private string $file;
	
	/**
     * The setUp method is called before each test
     */
    public function setUp() : void {
        $publicFolder = __DIR__.'/../../../../../../../public';
        require_once $publicFolder.'/wp-content/plugins/kc/Core/Files/FileService.php';
		$this->fileService = new FileService();
		$this->file = $publicFolder.'/index.php';
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