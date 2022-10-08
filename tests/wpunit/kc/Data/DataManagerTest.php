<?php
namespace Tests\WPUnit\KC\Data;

use KC\Data\DataManager;
use \Codeception\TestCase\WPTestCase;

/**
 * The DataManagerTest class contains methods to test the DataManager class
 */
class DataManagerTest extends WPTestCase {

    private DataManager $dataManager;

    /**
     * The _before method is called before each test
     */
    protected function _before() {
        require_once '../../public/wp-content/plugins/kc/Data/DataManager.php';
        $this->dataManager = new DataManager();
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
}