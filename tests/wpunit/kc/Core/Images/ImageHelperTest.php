<?php
namespace Tests\WPUnit\KC\Core\Images;

use KC\Core\Images\ImageHelper;
use \Codeception\TestCase\WPTestCase;

/**
 * The ImageHelperTest class contains methods to test the ImageHelper class
 */
final class ImageHelperTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/Images/ImageHelper.php';
    }

    /**
     * Test the getImageUrl method
     */
    public function testGetImageUrl() : void {
        $expected = '';
        $imageId = 0;
        $this->assertEquals($expected, ImageHelper::getImageUrl($imageId));
    }
}