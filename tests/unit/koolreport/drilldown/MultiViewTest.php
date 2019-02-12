<?php
namespace koolreport\drilldown;

class MultiViewTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    

    protected function views()
    {
        return array(
            array(
                "handler"=>"<i></i>",
                "widget"=>array(\koolreport\widgets\koolphp\Table::class,array(
                    "dataSource"=>array()
                ))
            )
        );
    }

    // tests
    public function testCssClass()
    {
        //"panel","header","title","body","handler","content"
        $content = MultiView::html(array(
            "name"=>"test",
            "dataSource"=>array(array("name"=>"Tuan","age"=>15)),
            "cssClass"=>array(
                "panel"=>"test-panel",
                "header"=>"test-header",
                "title"=>"test-title",
                "handler"=>"test-handler",
                "body"=>"test-body",
                "content"=>"test-content",
            ),
            "views"=>$this->views()
        ));
        $this->assertContains("test-panel",$content);
        $this->assertContains("test-header",$content);
        $this->assertContains("test-title",$content);
        $this->assertContains("test-handler",$content);
        $this->assertContains("test-body",$content);
        $this->assertContains("test-content",$content);

        //Test bs4
        $content = MultiView::html(array(
            "name"=>"test",
            "dataSource"=>array(array("name"=>"Tuan","age"=>15)),
            "themeBase"=>"bs4",
            "cssClass"=>array(
                "panel"=>"test-panel",
                "header"=>"test-header",
                "title"=>"test-title",
                "handler"=>"test-handler",
                "body"=>"test-body",
                "content"=>"test-content",
            ),
            "views"=>$this->views()
        ));
        $this->assertContains("test-panel",$content);
        $this->assertContains("test-header",$content);
        $this->assertContains("test-title",$content);
        $this->assertContains("test-handler",$content);
        $this->assertContains("test-body",$content);
        $this->assertContains("test-content",$content);

    }
}