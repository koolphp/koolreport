<?php

require_once "../../../../koolreport/autoload.php";

use \koolreport\KoolReport;

class Intro extends KoolReport
{
    use \koolreport\clients\Bootstrap;
    use \koolreport\inputs\Bindable;
    use \koolreport\inputs\POSTBinding;

    protected function defaultParamValues()
    {
        return array(
            "dateRange"=>array(date("Y-m-d 00:00:00"),date("Y-m-d 23:59:59")),
            "textBox"=>"KoolReport is great",
            "select"=>"",
            "multiSelect"=>array(),
            "radioList"=>"",
            "checkBoxList"=>array(),
            "startDatePicker"=>date("Y-m-d 00:00:00"),
            "endDatePicker"=>date("Y-m-d 23:59:59")
        );
    }

    protected function bindParamsToInputs()
    {
        return array(
            "dateRange",
            "select",
            "multiSelect",
            "textBox",
            "radioList",
            "checkBoxList",
            "startDatePicker",
            "endDatePicker",
        );
    }

    public function settings()
    {
        $config = include "../../../config.php";
        return array(
            "dataSources"=>array(
                "automaker"=>$config["automaker"]
            )
        );
    }   
    protected function setup()
    {
        $this->src("automaker")->query("
            SELECT
                customerNumber,
                customerName
            FROM
                customers
            ORDER BY customerName
            LIMIT 40,5
        ")
        ->pipe($this->dataStore("customers"));
    } 
}
