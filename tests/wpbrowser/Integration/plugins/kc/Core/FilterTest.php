<?php
namespace Tests\WPBrowser\Integration\Plugins\KC\Core;

use KC\Core\Filter;
use KC\Core\PostTypes\PostType;
use lucatume\WPBrowser\TestCase\WPTestCase;

/**
 * The FilterTest class contains methods to test the Filter class
 */
final class FilterTest extends WPTestCase {

    /**
     * The setUp method is called before each test
     */
    public function setUp() : void {
        $coreFolder = __DIR__.'/../../../../../../public/wp-content/plugins/kc/Core';
        require_once $coreFolder.'/Filter.php';
        require_once $coreFolder.'/PostTypes/PostType.php';
    }

    /**
     * Test the getManagePostsColumnsFilter method
     */
    public function testGetManagePostsColumnsFilter() : void {
        $expected = 'manage_kc_image_posts_columns';
        $this->assertEquals($expected, Filter::getManagePostsColumnsFilter(PostType::Image));
    }

    /**
     * Test the getPreUpdateOptionFilter method
     */
    public function testGetPreUpdateOptionFilter() : void {
        $expected = 'pre_update_option_kc-option';
        $this->assertEquals($expected, Filter::getPreUpdateOptionFilter('kc-option'));
    }
}