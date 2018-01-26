<?php

require_once "../../../../koolreport/autoload.php";

use \koolreport\processes\Group;
use \koolreport\processes\Sort;
use \koolreport\processes\Limit;
use \koolreport\processes\CopyColumn;
use \koolreport\processes\OnlyColumn;

class SalesByCustomer extends \koolreport\KoolReport
{
  use \koolreport\clients\FontAwesome;
  
  function settings()
  {
    return array(
      "dataSources"=>array(
        "sales"=>array(
            "class"=>'\koolreport\datasources\CSVDataSource',
            "filePath"=>"../../../databases/customer_product_dollarsales2.csv",
            "fieldSeparator"=>";"
        ),        
      )
    );
  }
  
  function setup()
  {
        $this->src('sales')
        ->pipe(new Group(array(
            "by"=>"customerName",
            "sum"=>"dollar_sales"
        )))
        ->pipe(new Limit(array(30)))
        ->pipe(new CopyColumn(array(
          "indicator"=>"dollar_sales",
        )))
        ->pipe($this->dataStore('sales_by_customer'));
  }
}