<?php
namespace koolreport\datagrid;

class DataTablesTest extends \Codeception\Test\Unit
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
    public function testRender()
    {
        $this->assertContains("KoolReport.widget.init",DataTables::html(array(
            "dataSource"=>array(
                array("name"=>"Peter","age"=>35),
                array("name"=>"David","age"=>36),
            )
        )));
    }
}