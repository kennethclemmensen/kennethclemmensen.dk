<?php
namespace Tests\WPBrowser\Integration\Plugins\KC\Data\Database;

use KC\Core\Images\ImageService;
use KC\Core\PostTypes\PostType;
use KC\Core\PostTypes\PostTypeService;
use KC\Core\Security\SecurityService;
use KC\Data\Database\DataManager;
use lucatume\WPBrowser\TestCase\WPTestCase;

/**
 * The DataManagerTest class contains methods to test the DataManager class
 */
final class DataManagerTest extends WPTestCase {

    private DataManager $dataManager;

    /**
     * The setUp method is called before each test
     */
    public function setUp() : void {
		$kcFolder = __DIR__.'/../../../../../../../public/wp-content/plugins/kc';
		require_once $kcFolder.'/Core/PostTypes/PostTypeFeature.php';
		require_once $kcFolder.'/Core/PostTypes/SortingOrder.php';
		require_once $kcFolder.'/Core/Taxonomies/TaxonomyName.php';
		require_once $kcFolder.'/Data/Database/DataManager.php';
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