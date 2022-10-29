<?php
namespace Tests\WPUnit\KC\Core\PostTypes;

use KC\Core\PostTypes\FieldName;
use \Codeception\TestCase\WPTestCase;

/**
 * The FieldNameTest class contains methods to test the FieldName enum
 */
final class FieldNameTest extends WPTestCase {

	/**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/PostTypes/FieldName.php';
    }

	/**
	 * Test the AltKey value
	 */
	public function testAltKeyValue() : void {
		$this->assertEquals('kc_page_alt_key', FieldName::AltKey->value);
	}

	/**
	 * Test the CtrlKey value
	 */
	public function testCtrlKeyValue() : void {
		$this->assertEquals('kc_page_ctrl_key', FieldName::CtrlKey->value);
	}

	/**
	 * Test the File value
	 */
	public function testFileValue() : void {
		$this->assertEquals('kc_file_file', FieldName::File->value);
	}

	/**
	 * Test the FileDescription value
	 */
	public function testFileDescriptionValue() : void {
		$this->assertEquals('kc_file_description', FieldName::FileDescription->value);
	}

	/**
	 * Test the FileDownloads value
	 */
	public function testFileDownloadsValue() : void {
		$this->assertEquals('kc_file_downloads', FieldName::FileDownloads->value);
	}

	/**
	 * Test the ImageGallery value
	 */
	public function testImageGalleryValue() : void {
		$this->assertEquals('kc_image_gallery', FieldName::ImageGallery->value);
	}

	/**
	 * Test the Key value
	 */
	public function testKeyValue() : void {
		$this->assertEquals('kc_page_key', FieldName::Key->value);
	}

	/**
	 * Test the ParentPage value
	 */
	public function testParentPageValue() : void {
		$this->assertEquals('kc_gallery_parent_page', FieldName::ParentPage->value);
	}

	/**
	 * Test the ShiftKey value
	 */
	public function testShiftKeyValue() : void {
		$this->assertEquals('kc_page_shift_key', FieldName::ShiftKey->value);
	}
}