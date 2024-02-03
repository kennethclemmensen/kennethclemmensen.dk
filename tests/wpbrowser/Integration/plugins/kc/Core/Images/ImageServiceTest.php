<?php
namespace Tests\WPBrowser\Integration\Plugins\KC\Core\Images;

use KC\Core\Images\ImageService;
use lucatume\WPBrowser\TestCase\WPTestCase;

/**
 * The ImageServiceTest class contains methods to test the ImageService class
 */
final class ImageServiceTest extends WPTestCase {

	/**
     * The setUp method is called before each test
     */
    public function setUp() : void {
		$coreFolder = __DIR__.'/../../../../../../../public/wp-content/plugins/kc/Core';
		require_once $coreFolder.'/Images/ImageService.php';
        require_once $coreFolder.'/Images/ImageSize.php';
		require_once $coreFolder.'/Security/SecurityService.php';
    }

    /**
     * Test the getImageUrl method
     */
    public function testGetImageUrl() : void {
        $imageService = new ImageService();
        $expected = '';
        $imageId = 0;
        $this->assertEquals($expected, $imageService->getImageUrl($imageId));
    }
}