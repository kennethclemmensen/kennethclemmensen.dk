<?php
namespace KC\Gallery;

use \Codeception\TestCase\WPTestCase;

/**
 * The GalleryModuleTest class contains methods to test the GalleryModule class
 */
class GalleryModuleTest extends WPTestCase {

    /**
     * Test the getGalleries method
     */
    public function testGetGalleries() : void {
        $galleryModule = new GalleryModule();
        $expected = 0;
        $this->assertEquals($expected, count($galleryModule->getGalleries()));
    }
}