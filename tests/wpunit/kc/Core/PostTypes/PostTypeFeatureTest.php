<?php
namespace Tests\WPUnit\KC\Core\PostTypes;

use KC\Core\PostTypes\PostTypeFeature;
use \Codeception\TestCase\WPTestCase;

/**
 * The PostTypeFeatureTest class contains methods to test the PostTypeFeature enum
 */
final class PostTypeFeatureTest extends WPTestCase {

	/**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/PostTypes/PostTypeFeature.php';
    }

	/**
	 * Test the Editor value
	 */
	public function testEditorValue() : void {
		$this->assertEquals('editor', PostTypeFeature::Editor->value);
	}

	/**
	 * Test the Thumbnail value
	 */
	public function testThumbnailValue() : void {
		$this->assertEquals('thumbnail', PostTypeFeature::Thumbnail->value);
	}

	/**
	 * Test the Title value
	 */
	public function testTitleValue() : void {
		$this->assertEquals('title', PostTypeFeature::Title->value);
	}
}