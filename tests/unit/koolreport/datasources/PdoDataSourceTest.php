<?php
namespace koolreport\datasources;

class PdoDataSourceTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $settings = array(
        "connectionString"=>"mysql:host=localhost;dbname=automaker",
        "username"=>"root",
        "password"=>"",
        "charset"=>"utf8"
    );

    public function testCreateSource()
    {
        $source = PdoDataSource::create($this->settings);
        $this->assertNotNull($source,"Could not create source");
    }

    public function testQuery()
    {
        $source = PdoDataSource::create($this->settings);
        $source->query("select * from tables");
        $this->assertEquals($this->tester->getPrivateProperty($source,"query"),"select * from tables");
    }
}