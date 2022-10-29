<?php
namespace Tests\WPUnit\KC\Core\Translations;

use KC\Core\Translations\TranslationHelper;
use KC\Core\Translations\TranslationString;
use \Codeception\TestCase\WPTestCase;

/**
 * The TranslationHelperTest class contains methods to test the TranslationHelper class
 */
final class TranslationHelperTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/Translations/TranslationHelper.php';
    }

    /**
     * Test the getTranslatedString method
     */
    public function testGetTranslatedString() : void {
        $expected = 'Backup';
        $this->assertEquals($expected, TranslationHelper::getTranslatedString(TranslationString::Backup));
    }
}