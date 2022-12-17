<?php
namespace Tests\WPUnit\KC\Core\Images;

use KC\Core\Images\ImageService;
use \Codeception\TestCase\WPTestCase;

/**
 * The ImageServiceTest class contains methods to test the ImageService class
 */
final class ImageServiceTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/Images/ImageService.php';
    }

    /**
     * Test the getImageUrl method
     */
    public function testGetImageUrl() : void {
        $expected = '';
        $imageId = 0;
        $this->assertEquals($expected, ImageService::getImageUrl($imageId));
    }
}