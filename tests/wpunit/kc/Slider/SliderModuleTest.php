<?php
namespace KC\Slider;

use \Codeception\TestCase\WPTestCase;

/**
 * The SliderModuleTest class contains methods to test the SliderModule class
 */
class SliderModuleTest extends WPTestCase {

    /**
     * Test the getSlides method
     */
    public function testGetSlides() : void {
        $sliderModule = new SliderModule();
        $expected = 0;
        $this->assertEquals($expected, count($sliderModule->getSlides()));
    }    
}