<?php
namespace KC\Core;

use \Codeception\TestCase\WPTestCase;

/**
 * The ActionTest class contains methods to test the Action class
 */
class ActionTest extends WPTestCase {
    
    /**
     * Test the getSavePostAction method
     */
    public function testGetSavePostAction() : void {
        $expected = 'save_post_kc_gallery';
        $this->assertEquals($expected, Action::getSavePostAction(PostType::GALLERY));
    }

    /**
     * Test the getManagePostsCustomColumn method
     */
    public function testGetManagePostsCustomColumn() : void {
        $expected = 'manage_kc_image_posts_custom_column';
        $this->assertEquals($expected, Action::getManagePostsCustomColumn(PostType::IMAGE));
    }
}