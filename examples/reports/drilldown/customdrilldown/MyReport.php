<?php

require_once "CountrySale.php";
require_once "CitySale.php";

class MyReport extends \koolreport\KoolReport
{
    use \koolreport\core\SubReport;
    function settings()
    {
        return array(
            "subReports"=>array(
                "countrySale"=>CountrySale::class,
                "citySale"=>CitySale::class,
            )
        );
    }
}