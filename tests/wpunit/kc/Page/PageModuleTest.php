<?php
namespace KC\Page;

use \Codeception\TestCase\WPTestCase;

/**
 * The PageModuleTest class contains methods to test the PageModule class
 */
class PageModuleTest extends WPTestCase {

    /**
     * Test the getPagesByTitle method
     */
    public function testGetPagesByTitle() : void {
        $pageModule = new PageModule();
        $expected = 0;
        $this->assertEquals($expected, count($pageModule->getPagesByTitle('')));
    }
}