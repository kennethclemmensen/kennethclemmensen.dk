<?php
namespace Tests\WPBrowser\Integration\Plugins\KC\Gallery\Settings;

use KC\Core\Translations\TranslationService;
use KC\Gallery\Settings\GallerySettings;
use lucatume\WPBrowser\TestCase\WPTestCase;

/**
 * The GallerySettingsTest class contains methods to test the GallerySettings class
 */
final class GallerySettingsTest extends WPTestCase {

    /**
     * The setUp method is called before each test
     */
    public function setUp() : void {
		$kcFolder = __DIR__.'/../../../../../../../public/wp-content/plugins/kc';
		require_once $kcFolder.'/Core/Settings/BaseSettings.php';
		require_once $kcFolder.'/Gallery/Settings/GallerySettings.php';
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