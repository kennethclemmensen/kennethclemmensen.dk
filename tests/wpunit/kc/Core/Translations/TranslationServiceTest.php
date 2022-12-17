<?php
namespace Tests\WPUnit\KC\Core\Translations;

use KC\Core\Translations\TranslationService;
use KC\Core\Translations\TranslationString;
use \Codeception\TestCase\WPTestCase;

/**
 * The TranslationServiceTest class contains methods to test the TranslationService class
 */
final class TranslationServiceTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/Translations/TranslationService.php';
    }

    /**
     * Test the getTranslatedString method
     */
    public function testGetTranslatedString() : void {
        $expected = 'Backup';
        $this->assertEquals($expected, TranslationService::getTranslatedString(TranslationString::Backup));
    }
}