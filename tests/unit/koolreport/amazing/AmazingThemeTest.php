<?php
namespace koolreport\amazing;

class AmazingThemeTest extends \Codeception\Test\Unit
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
    public function testDoesSupport()
    {
        $report = new \koolreport\KoolReport;
        $theme = new AmazingTheme($report);
        $widget = new \koolreport\datagrid\DataTables(array(
            "dataSource"=>array(
                array("name"=>"Tuan","age"=>35)
            )
        ));
        $this->assertTrue($theme->doesSupport($widget));
    }

    public function testAddingAssetUrl()
    {
        $theme = new AmazingTheme(null);
        $assetUrl = "assetUrl/url";
        $resources = array("a.js",array("b.js"));
        $resources = $this->tester->runPrivateMethod($theme,"addingAssetUrl",array($assetUrl,$resources));
        $this->assertEquals($resources,array(
            "assetUrl/url/a.js",
            array("assetUrl/url/b.js")
        ));
    }

    public function testApplyColorScheme()
    {
        $theme = new AmazingTheme(null);

        // $colorScheme = null;
        // $theme->applyColorScheme($colorScheme);
        // $this->assertContains("#000",$colorScheme,"Failed to get default color");

        // $colorScheme = "gray";
        // $theme->applyColorScheme($colorScheme);
        // $this->assertContains("#001",$colorScheme,"Failed to get gray color");

        $colorScheme = null;
        $theme->applyColorScheme($colorScheme);
        $this->assertEquals($colorScheme,null,"It should be null");


    }
}