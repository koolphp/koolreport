<?php
namespace koolreport\drilldown;

class DrillDownTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    

    protected function levels()
    {
        return array(
            array(
                "title"=>"Level 1",
                "content"=>function($params,$scope)
                {
                    echo "Level 1 content";
                }
            ),
            array(
                "title"=>"Level 2",
                "content"=>function($params,$scope)
                {
                    echo "Level 2 content";
                }
            ),            
        );
    }

    // tests
    public function testCssClass()
    {
        $content = DrillDown::html(array(
            "name"=>"testDrillDown",
            "cssClass"=>array(
                "panel"=>"test-panel",
                "header"=>"test-header",
                "title"=>"test-title",
                "btnBack"=>"test-btnBack",
                "body"=>"test-body",
                "levelTitle"=>"test-levelTitle",
            ),
            "levels"=>$this->levels()
        ));
        $this->assertContains("test-panel",$content);
        $this->assertContains("test-header",$content);
        $this->assertContains("test-title",$content);
        $this->assertContains("test-btnBack",$content);
        $this->assertContains("test-body",$content);
        $this->assertContains("test-levelTitle",$content);

        //Test bs4
        $content = DrillDown::html(array(
            "name"=>"testDrillDown",
            "themeBase"=>"bs4",
            "cssClass"=>array(
                "panel"=>"test-panel",
                "header"=>"test-header",
                "title"=>"test-title",
                "btnBack"=>"test-btnBack",
                "body"=>"test-body",
                "levelTitle"=>"test-levelTitle",
            ),
            "levels"=>$this->levels()
        ));
        $this->assertContains("test-panel",$content);
        $this->assertContains("test-header",$content);
        $this->assertContains("test-title",$content);
        $this->assertContains("test-btnBack",$content);
        $this->assertContains("test-body",$content);
        $this->assertContains("test-levelTitle",$content);
    }
}