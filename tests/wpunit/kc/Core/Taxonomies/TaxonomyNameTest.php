<?php
namespace Tests\WPUnit\KC\Core\Taxonomies;

use KC\Core\Taxonomies\TaxonomyName;
use \Codeception\TestCase\WPTestCase;

/**
 * The TaxonomyNameTest class contains methods to test the TaxonomyName enum
 */
final class TaxonomyNameTest extends WPTestCase {

	/**
     * The _before method is called before each test
     */
    protected function _before() : void {
        require_once '../../public/wp-content/plugins/kc/Core/Taxonomies/TaxonomyName.php';
    }

	/**
	 * Test the FileType value
	 */
	public function testFileTypeValue() : void {
		$this->assertEquals('kc_tax_file_type', TaxonomyName::FileType->value);
	}
}