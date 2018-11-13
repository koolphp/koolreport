<?php
namespace koolreport\core;

class DataStoreTest extends \Codeception\Test\Unit
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

    public function testInitDataStore()
    {
        $store = new DataStore;
        $this->assertEquals($store->data(),array(),"Data should be empty array");
        $this->assertEquals($store->meta(),array("columns"=>array()));

        $dummyData = array(
            array("name"=>"Peter","age"=>32),
            array("name"=>"David","age"=>23),
            array("name"=>"Michael","age"=>44),
        );

        $store = new DataStore($dummyData);
        $this->assertEquals($store->meta(),array(
            "columns"=>array(
                "name"=>array("type"=>"string"),
                "age"=>array("type"=>"number"),
            )
        ));
    }

    // tests
    public function testWhereIn()
    {    

        $store = new DataStore(array(
            array("name"=>"Peter","age"=>32),
            array("name"=>"David","age"=>23),
            array("name"=>"Michael","age"=>44),
        ));

        //Normal condition
        $sub = $store->whereIn("age",[32,44]);
        $this->assertEquals($sub->data(),array(
            array("name"=>"Peter","age"=>32),
            array("name"=>"Michael","age"=>44),
        ));

        //Empty in params
        $sub = $store->whereIn("age",[]);
        $this->assertEquals($sub->data(),array());
    }

    public function testAggregate()
    {
        $store = new DataStore(array(
            array("name"=>"Peter","age"=>32),
            array("name"=>"David","age"=>23),
            array("name"=>"Michael","age"=>44),
        ));

        $this->assertEquals($store->sum("age"),99,"sum() method has problem");
        $this->assertEquals($store->count(),3,"count() method has problem");
        $this->assertEquals($store->avg("age"),33,"avg() method has problem");
        $this->assertEquals($store->min("age"),23,"min() method has problem");
        $this->assertEquals($store->max("age"),44,"max() method has problem");
    }

    public function testEachMethod()
    {
        $store = new DataStore(array(
            array("name"=>"Peter","age"=>32),
            array("name"=>"David","age"=>23),
            array("name"=>"Michael","age"=>44),
        ));
        $result = array();
        $store->each(function($row) use (&$result){
            array_push($result,$row);
        });
        $this->assertEquals($result[0],array("name"=>"Peter","age"=>32));
        $this->assertEquals($result[2],array("name"=>"Michael","age"=>44));
    
        $store->each(function($row){
            return array("name"=>"New","age"=>0);
        });

        $this->assertEquals($store[0],array("name"=>"New","age"=>0));
        $this->assertEquals($store[2],array("name"=>"New","age"=>0));
    }

    public function testBreakGroup()
    {
        $store = new DataStore(array(
            array("name"=>"Peter","age"=>1),
            array("name"=>"Peter","age"=>2),
            array("name"=>"Michael","age"=>5),
        ));

        $result = array();
        $store->breakGroup("name",function($sub) use (&$result){
            $result[] = $sub->sum("age");
        });
        $this->assertEquals($result,array(3,5));
    }
}