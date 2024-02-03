<?php
namespace Tests\WPBrowser\Integration\Plugins\KC\Core\Translations;

use KC\Core\Translations\TranslationService;
use KC\Core\Translations\TranslationString;
use lucatume\WPBrowser\TestCase\WPTestCase;

/**
 * The TranslationServiceTest class contains methods to test the TranslationService class
 */
final class TranslationServiceTest extends WPTestCase {

    /**
     * The setUp method is called before each test
     */
    public function setUp() : void {
		$translationsFolder = __DIR__.'/../../../../../../../public/wp-content/plugins/kc/Core/Translations';
		require_once $translationsFolder.'/TranslationService.php';
		require_once $translationsFolder.'/TranslationString.php';
    }

    /**
     * Test the getTranslatedString method
     */
    public function testGetTranslatedString() : void {
        $translationService = new TranslationService();
        $expected = 'Backup';
        $this->assertEquals($expected, $translationService->getTranslatedString(TranslationString::Backup));
    }
}