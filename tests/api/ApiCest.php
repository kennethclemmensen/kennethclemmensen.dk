<?php
namespace Tests\Api;

use \ApiTester;

/**
 * The ApiCest class contains methods to test the api
 */
final class ApiCest {

    /**
     * Test the pages endpoint
     *
     * @param ApiTester $I the api tester
     */
    public function getPages(ApiTester $I) : void {
        $I->sendGET('/pages/title');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * Test the files endpoint
     *
     * @param ApiTester $I the api tester
     */
    public function getFiles(ApiTester $I) : void {
        $I->sendGET('/files?type=4');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * Test the file downloads counter endpoint
     *
     * @param ApiTester $I the api tester
     */
    public function updateFileDownloadsCounter(ApiTester $I) : void {
        $I->sendPUT('/fileDownloads', ['fileid' => 1558]);
        $I->seeResponseCodeIs(200);
    }    

    /**
     * Test the slides endpoint
     *
     * @param ApiTester $I the api tester
     */
    public function getSlides(ApiTester $I) : void {
        $I->sendGET('/slides');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * Test the galleries endpoint
     *
     * @param ApiTester $I the api tester
     */
    public function getGalleries(ApiTester $I) : void {
        $I->sendGET('/galleries');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * Test the galleries endpoint with the id parameter
     *
     * @param ApiTester $I the api tester
     */
    public function getGalleriesWithIdParameter(ApiTester $I) : void {
        $I->sendGET('/galleries/1740');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}