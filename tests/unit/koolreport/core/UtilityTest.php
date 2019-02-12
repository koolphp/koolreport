<?php
namespace koolreport\core;

class UtilityTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    

    // tests
    public function testStrReplaceFirst()
    {
        $str = "hello hello world!";
        $this->assertEquals(Utility::str_replace_first("hello","hi",$str),"hi hello world!");
    }

    public function testFormat()
    {
        $this->assertEquals(
            Utility::format(
                1234.12,
                array(
                    "type"=>"number",
                    "decPoint"=>",",
                    "thousandSep"=>".",
                    "decimals"=>1
                )
            ),
            "1.234,1"
        );

        $this->assertEquals(
            Utility::format(
                1234.12,
                array(
                    "type"=>"number",
                    "dec_point"=>",",
                    "thousand_sep"=>".",
                    "decimals"=>1
                )
            ),
            "1.234,1"
        );

        $this->assertEquals(
            Utility::format(
                1234.12,
                array(
                    "type"=>"number",
                    "decimalPoint"=>",",
                    "thousandSeparator"=>".",
                    "decimals"=>1
                )
            ),
            "1.234,1"
        );

        $this->assertEquals(
            Utility::format(
                1234.12,
                array(
                    "type"=>"number",
                    "decimalPoint"=>",",
                    "thousandSeparator"=>".",
                    "decimals"=>2,
                    "prefix"=>"$",
                    "suffix"=>"USD"
                )
            ),
            "$1.234,12USD"
        );

    }
}