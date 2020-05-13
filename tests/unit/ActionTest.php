<?php
require_once __DIR__.'/../../public/wp-content/plugins/kc/Core/Action.php';

use Codeception\Test\Unit;
use KC\Core\Action;

/**
 * The ActionTest class contains methods to test the Action class
 */
class ActionTest extends Unit {

    /**
     * Test the getSavePostAction method
     */
    public function testGetSavePostAction() : void {
        $expected = 'save_post_gallery';
        $this->assertEquals($expected, Action::getSavePostAction('gallery'));
    }

    /**
     * Test the getManagePostsCustomColumn method
     */
    public function testGetManagePostsCustomColumn() : void {
        $expected = 'manage_image_posts_custom_column';
        $this->assertEquals($expected, Action::getManagePostsCustomColumn('image'));
    }
}