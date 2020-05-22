<?php
namespace KC\Image;

use \Codeception\TestCase\WPTestCase;

/**
 * The ImageModuleTest class contains methods to test the ImageModule class
 */
class ImageModuleTest extends WPTestCase {

    /**
     * Test the getImages method
     */
    public function testGetImages() : void {
        $imageModule = new ImageModule();
        $galleryId = 0;
        $expected = 0;
        $this->assertEquals($expected, count($imageModule->getImages($galleryId)));
    }
}