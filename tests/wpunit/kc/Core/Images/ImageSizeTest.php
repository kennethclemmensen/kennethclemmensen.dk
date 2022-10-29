<?php
namespace Tests\WPUnit\KC\Core\Images;

use KC\Core\Images\ImageSize;
use \Codeception\TestCase\WPTestCase;

/**
 * The ImageSizeTest class contains methods to test the ImageSize enum
 */
final class ImageSizeTest extends WPTestCase {

	/**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/Images/ImageSize.php';
    }

	/**
	 * Test the GalleryImage value
	 */
	public function testGalleryImageValue() : void {
		$this->assertEquals('kc-gallery-image', ImageSize::GalleryImage->value);
	}

	/**
	 * Test the Large value
	 */
	public function testLargeValue() : void {
		$this->assertEquals('large', ImageSize::Large->value);
	}

	/**
	 * Test the PostThumbnail value
	 */
	public function testPostThumbnailValue() : void {
		$this->assertEquals('post-thumbnail', ImageSize::PostThumbnail->value);
	}

	/**
	 * Test the Slides value
	 */
	public function testSlidesValue() : void {
		$this->assertEquals('kc-slides', ImageSize::Slides->value);
	}

	/**
	 * Test the Thumbnail value
	 */
	public function testThumbnailValue() : void {
		$this->assertEquals('thumbnail', ImageSize::Thumbnail->value);
	}
}