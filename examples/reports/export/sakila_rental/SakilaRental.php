<?php

require_once "../../../../koolreport/autoload.php";

use \koolreport\KoolReport;
use \koolreport\processes\Filter;
use \koolreport\processes\TimeBucket;
use \koolreport\processes\Group;
use \koolreport\processes\Limit;

class SakilaRental extends KoolReport
{
    use \koolreport\export\Exportable;
    
    public function settings()
    {
        return array(
            "dataSources"=>array(
                "sakila_rental"=>array(
                    "class"=>'\koolreport\datasources\CSVDataSource',
                    'filePath'=>dirname(__FILE__)."/sakila_rental.csv",
                )
            )
        );
    }   
    protected function setup()
    {
        $this->src('sakila_rental')
        ->pipe(new TimeBucket(array(
            "payment_date"=>"month"
        )))
        ->pipe(new Group(array(
            "by"=>"payment_date",
            "sum"=>"amount"
        )))
        ->pipe($this->dataStore('sale_by_month'));
    } 
}
