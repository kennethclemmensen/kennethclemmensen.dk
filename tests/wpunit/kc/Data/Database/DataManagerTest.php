<?php
namespace Tests\WPUnit\KC\Data\Database;

use KC\Core\Images\ImageService;
use KC\Core\PostTypes\PostType;
use KC\Core\PostTypes\PostTypeService;
use KC\Core\Security\SecurityService;
use KC\Data\Database\DataManager;
use \Codeception\TestCase\WPTestCase;

/**
 * The DataManagerTest class contains methods to test the DataManager class
 */
final class DataManagerTest extends WPTestCase {

    private DataManager $dataManager;

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Data/Database/DataManager.php';
        $this->dataManager = new DataManager(new PostTypeService(), new SecurityService(), new ImageService());
    }

    /**
     * Test the getPages method
     */
    public function testGetPages() : void {
        $expected = 0;
        $this->assertEquals($expected, count($this->dataManager->getPages()));
    }

    /**
     * Test the getPagesByTitle method
     */
    public function testGetPagesByTitle() : void {
        $expected = 0;
        $this->assertEquals($expected, count($this->dataManager->getPagesByTitle('')));
    }

    /**
     * Test the getSlides method
     */
    public function testGetSlides() : void {
        $expected = 0;
        $this->assertEquals($expected, count($this->dataManager->getSlides()));
    }

    /**
     * Test the getGalleries method
     */
    public function testGetGalleries() : void {
        $expected = 0;
        $this->assertEquals($expected, count($this->dataManager->getGalleries()));
    }

    /**
     * Test the getImages method
     */
    public function testGetImages() : void {
        $galleryId = 0;
        $expected = 0;
        $this->assertEquals($expected, count($this->dataManager->getImages($galleryId)));
    }

    /**
     * Test the getFiles method
     */
    public function testGetFiles() : void {
        $expected = 0;
        $this->assertEquals($expected, count($this->dataManager->getFiles([])));
    }

    /**
     * Test the getShortcuts method
     */
    public function testGetShortcuts() : void {
        $expected = 0;
        $this->assertEquals($expected, count($this->dataManager->getShortcuts()));
    }

    /**
     * Test the getAllPosts method
     */
    public function testGetAllPosts() : void {
        $expected = 0;
        $this->assertEquals($expected, count($this->dataManager->getAllPosts(PostType::Page)));
    }
}