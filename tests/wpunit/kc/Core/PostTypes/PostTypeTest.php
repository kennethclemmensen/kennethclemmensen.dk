<?php
namespace Tests\WPUnit\KC\Core\PostTypes;

use KC\Core\PostTypes\PostType;
use \Codeception\TestCase\WPTestCase;

/**
 * The PostTypeTest class contains methods to test the PostType enum
 */
final class PostTypeTest extends WPTestCase {

	/**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/PostTypes/PostType.php';
    }

	/**
	 * Test the File value
	 */
	public function testFileValue() : void {
		$this->assertEquals('kc_file', PostType::File->value);
	}

	/**
	 * Test the Gallery value
	 */
	public function testGalleryValue() : void {
		$this->assertEquals('kc_gallery', PostType::Gallery->value);
	}

	/**
	 * Test the Image value
	 */
	public function testImageValue() : void {
		$this->assertEquals('kc_image', PostType::Image->value);
	}

	/**
	 * Test the Page value
	 */
	public function testPageValue() : void {
		$this->assertEquals('page', PostType::Page->value);
	}

	/**
	 * Test the Slides value
	 */
	public function testSlidesValue() : void {
		$this->assertEquals('kc_slides', PostType::Slides->value);
	}
}