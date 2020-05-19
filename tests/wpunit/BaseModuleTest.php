<?php
namespace KC\Core;

use \Codeception\TestCase\WPTestCase;

/**
 * The BaseModuleTest class contains methods to test the BaseModule class
 */
class BaseModuleTest extends WPTestCase {
    
    /**
     * Test the getAllPosts method
     */
    public function testGetAllPosts() : void {
        $baseModule = new BaseModule();
        $expected = 0;
        $this->assertEquals($expected, count($baseModule->getAllPosts(PostType::GALLERY)));
    }
}