<?php
namespace Tests\WPUnit\KC\Core\PostTypes;

use KC\Core\PostTypes\Icon;
use \Codeception\TestCase\WPTestCase;

/**
 * The IconTest class contains methods to test the Icon enum
 */
class IconTest extends WPTestCase {

	/**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/PostTypes/Icon.php';
    }

	/**
	 * Test the Gallery value
	 */
	public function testGalleryValue() : void {
		$this->assertEquals('dashicons-format-gallery', Icon::Gallery->value);
	}

	/**
	 * Test the Image value
	 */
	public function testImageValue() : void {
		$this->assertEquals('dashicons-format-image', Icon::Image->value);
	}

	/**
	 * Test the Images value
	 */
	public function testImagesValue() : void {
		$this->assertEquals('dashicons-images-alt', Icon::Images->value);
	}
}