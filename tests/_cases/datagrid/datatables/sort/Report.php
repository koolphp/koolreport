<?php

use \koolreport\processes\Custom;

class Report extends \koolreport\KoolReport
{
    public function settings()
    {
        return array(
            "dataSources"=>array(
                "data"=>array(
                    "class"=>'\koolreport\datasources\CSVDataSource',
                    'filePath'=>dirname(__FILE__)."/data.csv",
                    "fieldSeparator"=>";"
                )
            )
        );
    }   
    protected function setup()
    {
        $this->src("data")
        ->pipe(Custom::process(function($row){
            $row["num"] = str_replace(".","",$row["num"]);
            $row["num"] = str_replace(",",".",$row["num"]);
            $row["num"] = (float)$row["num"];
            return $row;
        }))
        ->pipe($this->dataStore("data"));
    }
}