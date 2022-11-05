<?php
namespace Tests\WPUnit\KC\Core\PostTypes;

use KC\Core\PostTypes\FieldName;
use KC\Core\PostTypes\PostTypeHelper;
use \Codeception\TestCase\WPTestCase;

/**
 * The PostTypeHelperTest class contains methods to test the PostTypeHelper class
 */
final class PostTypeHelperTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/PostTypes/PostTypeHelper.php';
    }

    /**
     * Test the getFieldValue method
     */
    public function testGetFieldValue() : void {
        $expected = 0;
        $this->assertEquals($expected, PostTypeHelper::getFieldValue(FieldName::File, 0));
    }

    /**
     * Test the setFieldValue method
     */
    public function testSetFieldValue() : void {
        PostTypeHelper::setFieldValue('', FieldName::File, 0);
        $this->assertEquals(0, PostTypeHelper::getFieldValue(FieldName::File, 0));
    }
}