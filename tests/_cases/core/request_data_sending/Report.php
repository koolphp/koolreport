<?php
require_once "../../DbReport.php";

use \koolreport\processes\Sort;

class Report extends \koolreport\KoolReport
{
    use \koolreport\clients\Bootstrap;

    public function settings()
    {
        return array(
            "dataSources"=>array(
                "data"=>array(
                    "class"=>'\koolreport\datasources\CSVDataSource',
                    'filePath'=>dirname(__FILE__)."/data.csv",
                )
            )
        );
    }   
    protected function setup()
    {
        $this->src("data")
        ->pipe($this->dataStore("data"))->requestDataSending();
        
    }
}