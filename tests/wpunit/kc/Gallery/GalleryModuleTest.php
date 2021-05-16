<?php
namespace KC\Gallery;

use \Codeception\TestCase\WPTestCase;

/**
 * The GalleryModuleTest class contains methods to test the GalleryModule class
 */
class GalleryModuleTest extends WPTestCase {

    private GalleryModule $galleryModule;

    /**
     * The _before method is executed before each test
     */
    protected function _before() {
        $this->galleryModule = new GalleryModule();
    }

    /**
     * Test the getGalleries method
     */
    public function testGetGalleries() : void {
        $expected = 0;
        $this->assertEquals($expected, count($this->galleryModule->getGalleries()));
    }

    /**
     * Test the getImages method
     */
    public function testGetImages() : void {
        $galleryModule = new GalleryModule();
        $galleryId = 0;
        $expected = 0;
        $this->assertEquals($expected, count($this->galleryModule->getImages($galleryId)));
    }
}