<?php
namespace Tests\WPUnit\KC\Gallery\Settings;

use KC\Core\Translations\TranslationService;
use KC\Gallery\Settings\GallerySettings;
use \Codeception\TestCase\WPTestCase;

/**
 * The GallerySettingsTest class contains methods to test the GallerySettings class
 */
final class GallerySettingsTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Gallery/Settings/GallerySettings.php';
    }

	/**
	 * Test the getParentPagePath method
	 */
	public function testGetParentPagePath() : void {
        $gallerySettings = new GallerySettings(new TranslationService());
		$expected = '/';
		$this->assertEquals($expected, $gallerySettings->getParentPagePath());
	}
}