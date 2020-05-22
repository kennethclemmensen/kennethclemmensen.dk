<?php
namespace KC\Core;

use \Codeception\TestCase\WPTestCase;

/**
 * The FilterTest class contains methods to test the Filter class
 */
class FilterTest extends WPTestCase {

    /**
     * Test the getManagePostsColumnsFilter method
     */
    public function testGetManagePostsColumnsFilter() : void {
        $expected = 'manage_photo_posts_columns';
        $this->assertEquals($expected, Filter::getManagePostsColumnsFilter(PostType::IMAGE));
    }
}