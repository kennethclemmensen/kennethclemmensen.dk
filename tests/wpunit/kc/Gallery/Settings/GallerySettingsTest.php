<?php
namespace Tests\WPUnit\KC\Gallery\Settings;

use KC\Gallery\Settings\GallerySettings;
use \Codeception\TestCase\WPTestCase;

/**
 * The GallerySettingsTest class contains methods to test the GallerySettings class
 */
final class GallerySettingsTest extends WPTestCase {

    private GallerySettings $gallerySettings;

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Gallery/Settings/GallerySettings.php';
        $this->gallerySettings = new GallerySettings();
    }

	/**
	 * Test the getParentPagePath method
	 */
	public function testGetParentPagePath() : void {
		$expected = '/';
		$this->assertEquals($expected, $this->gallerySettings->getParentPagePath());
	}
}