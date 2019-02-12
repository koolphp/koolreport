<?php

use \koolreport\processes\ColumnMeta;
use \koolreport\processes\DateTimeFormat;
use \koolreport\processes\CopyColumn;
use \koolreport\processes\Group;
use \koolreport\processes\Sort;

class Report extends \koolreport\KoolReport
{
    use \koolreport\bootstrap3\Theme;
    function settings()
    {
        return array(
            "dataSources" => array(
                "payments"=>array(
                    'filePath' => dirname(__FILE__).'/payments.csv',
                    'class' => "\koolreport\datasources\CSVDataSource"      
                ), 
            )
        );
    }
    function setup()
    {
        $this->src("payments")
        ->pipe(new ColumnMeta(array(
            "paymentDate"=>array(
                "type"=>"date",
                "format"=>"Y-m-d"
            ),
            "amount"=>array(
                "type"=>'number'
            )
        )))
        ->pipe(new CopyColumn(array(
            "year"=>"paymentDate",
            "month"=>"paymentDate",
        )))
        ->pipe(new DateTimeFormat(array(
            "year"=>"Y",
            "month"=>"Y-m"
        )))
        ->pipe(new Group(array(
            "by"=>"month",
            "sum"=>"amount"
        )))
        // ->pipe(new Sort(array(
        //     "month"=>"desc",
        // )))
        ->pipe($this->dataStore("payments"));
    }
}