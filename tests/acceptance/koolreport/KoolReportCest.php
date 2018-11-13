<?php
namespace koolreport;

use \AcceptanceTester;

class KoolReportCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTestClientSideOnDone(AcceptanceTester $I)
    {
        $I->amOnPage("/core/clientside.ondone");
        $I->see("run");
    }
}
