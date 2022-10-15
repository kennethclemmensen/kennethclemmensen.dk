<?php
namespace Tests\WPUnit\KC\Core\PostTypes;

use KC\Core\PostTypes\FieldType;
use \Codeception\TestCase\WPTestCase;

/**
 * The FieldTypeTest class contains methods to test the FieldType enum
 */
class FieldTypeTest extends WPTestCase {

	/**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/PostTypes/FieldType.php';
    }

	/**
	 * Test the CheckBox value
	 */
	public function testCheckBoxValue() : void {
		$this->assertEquals('checkbox', FieldType::CheckBox->value);
	}

	/**
	 * Test the File value
	 */
	public function testFileValue() : void {
		$this->assertEquals('file_advanced', FieldType::File->value);
	}

	/**
	 * Test the Number value
	 */
	public function testNumberValue() : void {
		$this->assertEquals('number', FieldType::Number->value);
	}

	/**
	 * Test the Select value
	 */
	public function testSelectValue() : void {
		$this->assertEquals('select', FieldType::Select->value);
	}

	/**
	 * Test the TextArea value
	 */
	public function testTextAreaValue() : void {
		$this->assertEquals('textarea', FieldType::TextArea->value);
	}
}