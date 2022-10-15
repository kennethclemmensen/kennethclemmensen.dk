<?php
namespace Tests\WPUnit\KC\Core;

use KC\Core\Action;
use KC\Core\PostTypes\PostType;
use \Codeception\TestCase\WPTestCase;

/**
 * The ActionTest class contains methods to test the Action class
 */
class ActionTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/Action.php';
    }
    
    /**
     * Test the getSavePostAction method
     */
    public function testGetSavePostAction() : void {
        $expected = 'save_post_kc_gallery';
        $this->assertEquals($expected, Action::getSavePostAction(PostType::Gallery));
    }

    /**
     * Test the getManagePostsCustomColumn method
     */
    public function testGetManagePostsCustomColumn() : void {
        $expected = 'manage_kc_image_posts_custom_column';
        $this->assertEquals($expected, Action::getManagePostsCustomColumn(PostType::Image));
    }
}