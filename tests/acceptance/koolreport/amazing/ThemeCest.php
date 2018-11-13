<?php
namespace koolreport\amazing;

use \AcceptanceTester;
use \Codeception\Util\Locator;

class ThemeCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage('/amazing/general');
        $I->seeElement(Locator::contains('script','KoolReport'));
    }
}
