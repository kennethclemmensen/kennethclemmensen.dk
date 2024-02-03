<?php
namespace Tests\WPBrowser\Integration\Plugins\KC\Core\PostTypes;

use KC\Core\PostTypes\FieldName;
use KC\Core\PostTypes\PostTypeService;
use lucatume\WPBrowser\TestCase\WPTestCase;

/**
 * The PostTypeServiceTest class contains methods to test the PostTypeService class
 */
final class PostTypeServiceTest extends WPTestCase {

    private PostTypeService $postTypeService;

	/**
     * The setUp method is called before each test
     */
    public function setUp() : void {
		$postTypesFolder = __DIR__.'/../../../../../../../public/wp-content/plugins/kc/Core/PostTypes';
		require_once $postTypesFolder.'/FieldName.php';
		require_once $postTypesFolder.'/PostTypeService.php';
        $this->postTypeService = new PostTypeService();
    }

    /**
     * Test the getFieldValue method
     */
    public function testGetFieldValue() : void {
        $expected = 0;
        $this->assertEquals($expected, $this->postTypeService->getFieldValue(FieldName::File, 0));
    }

    /**
     * Test the setFieldValue method
     */
    public function testSetFieldValue() : void {
        $this->postTypeService->setFieldValue('', FieldName::File, 0);
        $this->assertEquals(0, $this->postTypeService->getFieldValue(FieldName::File, 0));
    }
}