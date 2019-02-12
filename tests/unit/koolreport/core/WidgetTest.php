<?php
namespace koolreport\core;

class WidgetTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    // tests
    public function testAttachResourceToEnd()
    {
        $widget = new Widget;
        $des = array("a",array("b"));
        $att = array("c");
        $this->tester->runPrivateMethod($widget,"attachResourceToEnd",array(&$des,$att));
        //$widget->attachResourceToEnd($des,$att);
        $this->assertEquals($des,array("a",array("b",array("c"))));
    }

    
}