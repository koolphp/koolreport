<?php
namespace koolreport\drilldown;

class FakeSubReport extends \koolreport\KoolReport
{

}

class MainReport extends \koolreport\KoolReport
{
    use \koolreport\core\SubReport;
    function settings()
    {
        return array(
            "subReports"=>array("fake"=>FakeSubReport::class)
        );
    }
}

class CustomDrillDownTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    // tests
    public function testCssClass()
    {
        $report = new MainReport;

        $content = CustomDrillDown::html(array(
            "name"=>"testDrillDown",
            "report"=>$report,
            "cssClass"=>array(
                "panel"=>"test-panel",
                "header"=>"test-header",
                "title"=>"test-title",
                "btnBack"=>"test-btnBack",
                "body"=>"test-body",
                "levelTitle"=>"test-levelTitle",
            ),
            "subReports"=>array("fake")
        ));
        $this->assertContains("test-panel",$content);
        $this->assertContains("test-header",$content);
        $this->assertContains("test-title",$content);
        $this->assertContains("test-btnBack",$content);
        $this->assertContains("test-body",$content);
        $this->assertContains("test-levelTitle",$content);

        //Test bs4
        $content = CustomDrillDown::html(array(
            "name"=>"testDrillDown",
            "themeBase"=>"bs4",
            "report"=>$report,
            "cssClass"=>array(
                "panel"=>"test-panel",
                "header"=>"test-header",
                "title"=>"test-title",
                "btnBack"=>"test-btnBack",
                "body"=>"test-body",
                "levelTitle"=>"test-levelTitle",
            ),
            "subReports"=>array("fake")
        ));
        $this->assertContains("test-panel",$content);
        $this->assertContains("test-header",$content);
        $this->assertContains("test-title",$content);
        $this->assertContains("test-btnBack",$content);
        $this->assertContains("test-body",$content);
        $this->assertContains("test-levelTitle",$content);

    }
}