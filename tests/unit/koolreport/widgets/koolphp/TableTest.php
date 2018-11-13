<?php
namespace koolreport\widgets\koolphp;

class TableTest extends \Codeception\Test\Unit
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
    public function testGrouping()
    {
        $table = new Table(array(
            "dataSource"=>array(
                array("name"=>"Peter","age"=>1),
                array("name"=>"Peter","age"=>2),
                array("name"=>"Michael","age"=>5),
            )
        ));

        

    }
}