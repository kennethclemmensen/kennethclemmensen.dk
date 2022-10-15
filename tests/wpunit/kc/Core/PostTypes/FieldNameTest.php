<?php
namespace Tests\WPUnit\KC\Core\PostTypes;

use KC\Core\PostTypes\FieldName;
use \Codeception\TestCase\WPTestCase;

/**
 * The FieldNameTest class contains methods to test the FieldName enum
 */
class FieldNameTest extends WPTestCase {

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
		$this->assertEquals('field_alt_key', FieldName::AltKey->value);
	}

	/**
	 * Test the CtrlKey value
	 */
	public function testCtrlKeyValue() : void {
		$this->assertEquals('field_ctrl_key', FieldName::CtrlKey->value);
	}

	/**
	 * Test the File value
	 */
	public function testFileValue() : void {
		$this->assertEquals('field_file', FieldName::File->value);
	}

	/**
	 * Test the FileDescription value
	 */
	public function testFileDescriptionValue() : void {
		$this->assertEquals('field_description', FieldName::FileDescription->value);
	}

	/**
	 * Test the FileDownloads value
	 */
	public function testFileDownloadsValue() : void {
		$this->assertEquals('field_download_counter', FieldName::FileDownloads->value);
	}

	/**
	 * Test the ImageGallery value
	 */
	public function testImageGalleryValue() : void {
		$this->assertEquals('photo_gallery', FieldName::ImageGallery->value);
	}

	/**
	 * Test the Key value
	 */
	public function testKeyValue() : void {
		$this->assertEquals('field_key', FieldName::Key->value);
	}

	/**
	 * Test the ParentPage value
	 */
	public function testParentPageValue() : void {
		$this->assertEquals('parent_page', FieldName::ParentPage->value);
	}

	/**
	 * Test the ShiftKey value
	 */
	public function testShiftKeyValue() : void {
		$this->assertEquals('field_shift_key', FieldName::ShiftKey->value);
	}
}