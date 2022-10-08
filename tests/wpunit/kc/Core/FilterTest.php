<?php
namespace Tests\WPUnit\KC\Core;

use KC\Core\Filter;
use KC\Core\PostTypes\PostType;
use \Codeception\TestCase\WPTestCase;

/**
 * The FilterTest class contains methods to test the Filter class
 */
class FilterTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() {
        require_once '../../public/wp-content/plugins/kc/Core/Filter.php';
    }

    /**
     * Test the getManagePostsColumnsFilter method
     */
    public function testGetManagePostsColumnsFilter() : void {
        $expected = 'manage_kc_image_posts_columns';
        $this->assertEquals($expected, Filter::getManagePostsColumnsFilter(PostType::Image));
    }
}