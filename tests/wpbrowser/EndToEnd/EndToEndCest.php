<?php
namespace Tests\WPBrowser\EndToEnd;

use Tests\Support\EndToEndTester;

/**
 * The EndToEndCest class contains end-to-end tests for the website
 */
final class EndToEndCest {

	/**
	 * Test the front page
	 * 
	 * @param EndToEndTester $I the end-to-end tester
	 */
	public function testFrontPage(EndToEndTester $I) : void {
		$I->wantToTest('the front page');
		$I->amOnPage('/');
		$I->see('Velkommen');
	}

	/**
	 * Test a gallery page
	 *
	 * @param EndToEndTester $I the end-to-end tester
	 */
	public function testGalleryPage(EndToEndTester $I) : void {
		$I->wantToTest('a gallery page');
		$I->amOnPage('/');
		$I->click('Billeder');
		$I->amOnPage('/billeder');
		$I->click('Fyn');
		$I->amOnPage('/billeder/fyn');
		$I->see('Fyn');
	}

	/**
	 * Test the search page
	 *
	 * @param EndToEndTester $I the end-to-end tester
	 */
	public function testSearchPage(EndToEndTester $I) : void {
		$I->wantToTest('the search page');
		$I->amOnPage('/');
		$I->click('Søg');
		$I->amOnPage('/soeg');
		$I->fillField('search', 'Film');
		$I->see('Søgeresultater');
	}
}