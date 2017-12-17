<?php
/**
 * The AcceptanceCest class contains acceptance tests for the website
 */
class AcceptanceCest {

    /**
     * Test the front page
     *
     * @param AcceptanceTester $I the acceptance tester
     */
    public function testFrontPage(AcceptanceTester $I) {
        $I->wantToTest('the front page');
        $I->amOnPage('/');
        $I->see('Velkommen');
    }

    /**
     * Test a gallery page
     *
     * @param AcceptanceTester $I the acceptance tester
     */
    public function testGalleryPage(AcceptanceTester $I) {
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
     * @param AcceptanceTester $I the acceptance tester
     */
    public function testSearchPage(AcceptanceTester $I) {
        $I->wantToTest('the search page');
        $I->amOnPage('/');
        $I->click('Søg');
        $I->amOnPage('/soeg');
        $I->fillField('search', 'Film');
        $I->see('Søgeresultater');
    }
}