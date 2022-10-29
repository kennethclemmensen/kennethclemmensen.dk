<?php
namespace Tests\WPUnit\KC\Core\Translations;

use KC\Core\Translations\TranslationString;
use \Codeception\TestCase\WPTestCase;

/**
 * The TranslationStringTest class contains methods to test the TranslationString enum
 */
final class TranslationStringTest extends WPTestCase {

    /**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/Translations/TranslationString.php';
    }

	/**
	 * Test the AltKey value
	 */
	public function testAltKeyValue() : void {
		$this->assertEquals('Alt key', TranslationString::AltKey->value);
	}

	/**
	 * Test the Backup value
	 */
	public function testBackupValue() : void {
		$this->assertEquals('Backup', TranslationString::Backup->value);
	}

	/**
	 * Test the CreateBackup value
	 */
	public function testCreateBackupValue() : void {
		$this->assertEquals('Create backup', TranslationString::CreateBackup->value);
	}

	/**
	 * Test the CtrlKey value
	 */
	public function testCtrlKeyValue() : void {
		$this->assertEquals('Ctrl key', TranslationString::CtrlKey->value);
	}

	/**
	 * Test the Delete value
	 */
	public function testDeleteValue() : void {
		$this->assertEquals('Delete', TranslationString::Delete->value);
	}

	/**
	 * Test the Description value
	 */
	public function testDescriptionValue() : void {
		$this->assertEquals('Description', TranslationString::Description->value);
	}

	/**
	 * Test the Download value
	 */
	public function testDownloadValue() : void {
		$this->assertEquals('Download', TranslationString::Download->value);
	}

	/**
	 * Test the DownloadCounter value
	 */
	public function testDownloadCounterValue() : void {
		$this->assertEquals('Download counter', TranslationString::DownloadCounter->value);
	}

	/**
	 * Test the File value
	 */
	public function testFileValue() : void {
		$this->assertEquals('File', TranslationString::File->value);
	}

	/**
	 * Test the Files value
	 */
	public function testFilesValue() : void {
		$this->assertEquals('Files', TranslationString::Files->value);
	}

	/**
	 * Test the FileInformations value
	 */
	public function testFileInformationsValue() : void {
		$this->assertEquals('File informations', TranslationString::FileInformations->value);
	}

	/**
	 * Test the FileType value
	 */
	public function testFileTypeValue() : void {
		$this->assertEquals('File type', TranslationString::FileType->value);
	}

	/**
	 * Test the FileTypes value
	 */
	public function testFileTypesValue() : void {
		$this->assertEquals('File types', TranslationString::FileTypes->value);
	}

	/**
	 * Test the Galleries value
	 */
	public function testGalleriesValue() : void {
		$this->assertEquals('Galleries', TranslationString::Galleries->value);
	}

	/**
	 * Test the Gallery value
	 */
	public function testGalleryValue() : void {
		$this->assertEquals('Gallery', TranslationString::Gallery->value);
	}

	/**
	 * Test the GalleryInformations value
	 */
	public function testGalleryInformationsValue() : void {
		$this->assertEquals('Gallery informations', TranslationString::GalleryInformations->value);
	}

	/**
	 * Test the Key value
	 */
	public function testKeyValue() : void {
		$this->assertEquals('Key', TranslationString::Key->value);
	}

	/**
	 * Test the Image value
	 */
	public function testImageValue() : void {
		$this->assertEquals('Image', TranslationString::Image->value);
	}

	/**
	 * Test the Images value
	 */
	public function testImagesValue() : void {
		$this->assertEquals('Images', TranslationString::Images->value);
	}

	/**
	 * Test the ImageHeight value
	 */
	public function testImageHeightValue() : void {
		$this->assertEquals('Image height', TranslationString::ImageHeight->value);
	}

	/**
	 * Test the ImageWidth value
	 */
	public function testImageWidthValue() : void {
		$this->assertEquals('Image width', TranslationString::ImageWidth->value);
	}

	/**
	 * Test the ImageInformations value
	 */
	public function testImageInformationsValue() : void {
		$this->assertEquals('Image informations', TranslationString::ImageInformations->value);
	}

	/**
	 * Test the MetaBoxIsNotActivated value
	 */
	public function testMetaBoxIsNotActivatedValue() : void {
		$this->assertEquals('Meta Box is not activated', TranslationString::MetaBoxIsNotActivated->value);
	}

	/**
	 * Test the ParentPage value
	 */
	public function testParentPageValue() : void {
		$this->assertEquals('Parent page', TranslationString::ParentPage->value);
	}

	/**
	 * Test the Settings value
	 */
	public function testSettingsValue() : void {
		$this->assertEquals('Settings', TranslationString::Settings->value);
	}

	/**
	 * Test the ShiftKey value
	 */
	public function testShiftKeyValue() : void {
		$this->assertEquals('Shift key', TranslationString::ShiftKey->value);
	}

	/**
	 * Test the Shortcut value
	 */
	public function testShortcutValue() : void {
		$this->assertEquals('Shortcut', TranslationString::Shortcut->value);
	}

	/**
	 * Test the Slide value
	 */
	public function testSlideValue() : void {
		$this->assertEquals('Slide', TranslationString::Slide->value);
	}

	/**
	 * Test the Slides value
	 */
	public function testSlidesValue() : void {
		$this->assertEquals('Slides', TranslationString::Slides->value);
	}
}