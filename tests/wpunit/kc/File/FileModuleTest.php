<?php
namespace KC\File;

use \Codeception\TestCase\WPTestCase;

/**
 * The FileModuleTest class contains methods to test the FileModule class
 */
class FileModuleTest extends WPTestCase {

    /**
     * Test the getFiles method
     */
    public function testGetFiles() : void {
        $fileModule = new FileModule();
        $expected = 0;
        $this->assertEquals($expected, count($fileModule->getFiles([])));
    }
}