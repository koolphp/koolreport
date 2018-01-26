<?php

class CountrySale extends \koolreport\KoolReport
{
    function settings()
    {
        //Get default connection from config.php
        $config = include "../../../config.php";
        return array(
            "dataSources"=>array(
                "automaker"=>$config["automaker"]
            )
        );

    }
    function setup()
    {
        $this->src("automaker")->query("
            SELECT country, sum(amount) as sale_amount
            FROM
                payments
            JOIN
                customers
            ON
                customers.customerNumber = payments.customerNumber
            GROUP BY country
        ")
        ->pipe($this->dataStore("country_sale"));
    }
}