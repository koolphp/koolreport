<?php
require_once "../../../../koolreport/autoload.php";
use \koolreport\processes\ColumnMeta;
use \koolreport\processes\Limit;
use \koolreport\processes\RemoveColumn;
use \koolreport\processes\OnlyColumn;
use \koolreport\processes\Sort;
use \koolreport\cube\processes\Cube;

class SalesCustomersProducts extends koolreport\KoolReport
{
  function settings()
  {
    return array(
      "dataSources"=>array(
        "sales"=>array(
          'filePath' => '../../../databases/customer_product_dollarsales2.csv',
          'fieldSeparator' => ';',
          'class' => "\koolreport\datasources\CSVDataSource"      
        ),
      )
    );
  }
  function setup()
  {
    $node = $this->src('sales')
    ->pipe(new ColumnMeta(array(
      "dollar_sales"=>array(
        'type' => 'number',
        "prefix"=>"$",
      ),
    )));
    
    $node->pipe(new Cube(array(
      "row" => "customerName",
      "column" => "productLine",
      "sum" => "dollar_sales"
    )))
    ->pipe(new Sort(array(
      '{{all}}' => 'desc'
    )))
    ->pipe(new Limit(array(
      5, 0
    )))
    ->pipe(new ColumnMeta(array(
      "{{all}}"=>array(
        "label"=>"Total",
      ),
      "customerName"=>array(
        "label"=>"Customers",
      ),
    )))->saveTo($node2);
    $node2->pipe($this->dataStore('salesCustomerProductLine'));  
    $node2->pipe(new RemoveColumn(array(
      "{{all}}"
    )))->pipe($this->dataStore('salesCustomerProductLineNoAll'));
    $node2->pipe(new OnlyColumn(array(
      'customerName', "{{all}}"
    )))->pipe($this->dataStore('salesCustomerProductLineAll'));  
  }
}
