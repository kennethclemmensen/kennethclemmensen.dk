<?php
class AcceptanceCest {

    public function frontpageWorks(AcceptanceTester $I) {
        $I->amOnPage('/');
        $I->see('Velkommen');
    }
}