<?php
/**
 * The ApiCest class contains methods to test the api
 */
class ApiCest {

    /**
     * Test the slides endpoint
     *
     * @param ApiTester $I the api tester
     */
    public function getSlides(\ApiTester $I) {
        $I->sendGET('/slides');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}