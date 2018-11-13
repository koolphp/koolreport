<?php
namespace koolreport\datagrid;
use \AcceptanceTester;

class DataTablesCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTestLanguage(AcceptanceTester $I)
    {
        $I->amOnPage("/datagrid/datatables/language");
        $I->see("Einträgen");//See if the page generate German language
    }
}
