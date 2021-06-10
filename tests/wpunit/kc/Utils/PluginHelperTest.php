<?php
namespace KC\Utils;

use \Codeception\TestCase\WPTestCase;

/**
 * The PluginHelperTest class contains methods to test the PluginHelper class
 */
class PluginHelperTest extends WPTestCase {

    /**
     * Test the getImageUrl method
     */
    public function testGetImageUrl() : void {
        $expected = '';
        $imageId = 0;
        $this->assertEquals($expected, PluginHelper::getImageUrl($imageId));
    }

    /**
     * Test the getFieldValue method
     */
    public function testGetFieldValue() : void {
        $expected = '';
        $this->assertEquals($expected, PluginHelper::getFieldValue('', 0));
    }

    /**
     * Test the getTranslatedString method
     */
    public function testGetTranslatedString() : void {
        $expected = 'Image';
        $this->assertEquals($expected, PluginHelper::getTranslatedString('Image'));
    }

    /**
     * Test the appendSlash method
     */
    public function testAppendSlash() : void {
        $str = 'test';
        PluginHelper::appendSlash($str);
        $this->assertEquals('test/', $str);
    }

    /**
     * Test the removeLastCharacter method
     */
    public function testRemoveLastCharacter() : void {
        $str = 'test';
        $this->assertEquals('tes', PluginHelper::removeLastCharacter($str));
    }
}