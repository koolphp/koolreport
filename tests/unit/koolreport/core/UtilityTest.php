<?php
namespace koolreport\core;

class UtilityTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testStrReplaceFirst()
    {
        $str = "hello hello world!";
        $this->assertEquals(Utility::str_replace_first("hello","hi",$str),"hi hello world!");
        $this->make("User",["name"=>"David"]);
    }
}