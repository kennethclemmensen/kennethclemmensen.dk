<?php
namespace Tests\WPBrowser\Integration\Plugins\KC\Core;

use KC\Core\Action;
use KC\Core\PostTypes\PostType;
use lucatume\WPBrowser\TestCase\WPTestCase;

/**
 * The ActionTest class contains methods to test the Action class
 */
final class ActionTest extends WPTestCase {

    /**
     * The setUp method is called before each test
     */
    public function setUp() : void {
        $coreFolder = __DIR__.'/../../../../../../public/wp-content/plugins/kc/Core';
        require_once $coreFolder.'/Action.php';
        require_once $coreFolder.'/PostTypes/PostType.php';
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