<?php
/**
 * The FunctionalCest class contains functional tests for the website
 */
class FunctionalCest {

    /**
     * Test the front page
     *
     * @param FunctionalTester $I the functional tester
     */
    public function testFrontPage(FunctionalTester $I) : void {
        $I->wantToTest('the front page');
        $I->amOnPage('/');
        $I->see('Velkommen');
    }

    /**
     * Test a gallery page
     *
     * @param FunctionalTester $I the functional tester
     */
    public function testGalleryPage(FunctionalTester $I) : void {
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
     * @param FunctionalTester $I the functional tester
     */
    public function testSearchPage(FunctionalTester $I) : void {
        $I->wantToTest('the search page');
        $I->amOnPage('/');
        $I->click('Søg');
        $I->amOnPage('/soeg');
        $I->fillField('search', 'Film');
        $I->see('Søgeresultater');
    }
}