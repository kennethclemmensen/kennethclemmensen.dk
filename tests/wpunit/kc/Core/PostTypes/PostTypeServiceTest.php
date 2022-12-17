<?php
namespace Tests\WPUnit\KC\Core\PostTypes;

use KC\Core\PostTypes\FieldName;
use KC\Core\PostTypes\PostTypeService;
use \Codeception\TestCase\WPTestCase;

/**
 * The PostTypeServiceTest class contains methods to test the PostTypeService class
 */
final class PostTypeServiceTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/PostTypes/PostTypeService.php';
    }

    /**
     * Test the getFieldValue method
     */
    public function testGetFieldValue() : void {
        $expected = 0;
        $this->assertEquals($expected, PostTypeService::getFieldValue(FieldName::File, 0));
    }

    /**
     * Test the setFieldValue method
     */
    public function testSetFieldValue() : void {
        PostTypeService::setFieldValue('', FieldName::File, 0);
        $this->assertEquals(0, PostTypeService::getFieldValue(FieldName::File, 0));
    }
}