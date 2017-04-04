<?php
require_once "../../../../koolreport/autoload.php";
use \koolreport\processes\ColumnMeta;
use \koolreport\processes\Limit;
use \koolreport\processes\Sort;
use \koolreport\processes\RemoveColumn;
use \koolreport\processes\OnlyColumn;
use \koolreport\processes\ValueMap;
use \koolreport\cube\processes\Cube;

class SalesQuarters extends koolreport\KoolReport
{
  function settings()
  {
    return array(
      "dataSources"=>array(
        // "sales"=>array(
          // 'connectionString' => 'mysql:host=localhost;dbname=automaker',
          // 'username' => 'root',
          // 'password' => '',
          // 'charset' => 'utf8',          
        // ),
        "sales"=>array(
          'filePath' => '../../../databases/customer_product_dollarsales2.csv',
          'class' => "\koolreport\datasources\CSVDataSource",      
          // 'filePath' => '../../../databases/customer_product_dollarsales2.xlsx',
          // 'class' => "\koolreport\datasources\ExcelDataSource",      
          'fieldSeparator' => ';',
        ),
        // "sales"=>array(
          // 'connectionString' => 'mongodb://localhost:27017',
          // 'database' => 'test',
          // 'class' => "\koolreport\datasources\MongoDataSource"
        // ),
      )
    );
  }
  function setup()
  {
    // $node = $this->src('sales')
    // ->query("SELECT customerName, productLine, productName, concat('Q', quarter(orderDate)) as orderQuarter, dollar_sales FROM customer_product_dollarsales2 WHERE orderDate <> '0000-00-00 00:00:00'");
    
    $node = $this->src('sales')
    ->pipe(new ColumnMeta(array(
      "dollar_sales"=>array(
        'type' => 'number',
        "prefix" => "$",
      ),
    )))
    ->pipe(new ValueMap(array(
      'orderQuarter' => array(
        '{func}' => function ($value) {
          return 'Q' . $value;
        },
      )
    )));
    
    $node->pipe(new Cube(array(
      "row" => "customerName",
      "column" => "orderQuarter",
      "sum" => "dollar_sales"
    )))
    ->pipe(new Sort(array(
      '{{all}}' => 'desc'
    )))
    ->pipe(new Limit(array(
      5, 0
    )))->pipe(new ColumnMeta(array(
      "{{all}}"=>array(
        "label"=>"Total",
      ),
      "customerName"=>array(
        "label"=>"Customer",
      ),
    )))->saveTo($node2);
    
    $node2->pipe($this->dataStore('salesQuarterCustomer'));
    
    $node2->pipe(new RemoveColumn(array(
      "{{all}}"
    )))
    ->pipe($this->dataStore('salesQuarterCustomerNoAll'));
    
    $node2->pipe(new OnlyColumn(array(
      'customerName', "{{all}}"
    )))->pipe($this->dataStore('salesQuarterCustomerAll'));
    
    $node->pipe(new Cube(array(
      "row" => "productName",
      "column" => "orderQuarter",
      "sum" => "dollar_sales"
    )))
    ->pipe(new Sort(array(
      '{{all}}' => 'desc'
    )))
    ->pipe(new Limit(array(
      5, 0
    )))->pipe(new ColumnMeta(array(
      "{{all}}"=>array(
        "label"=>"Total",
      ),
      "productName"=>array(
        "label"=>"Product",
      ),
    )))->saveTo($node2);
    
    $node2->pipe($this->dataStore('salesQuarterProductName'));
    
    $node2->pipe(new RemoveColumn(array(
      "{{all}}"
    )))
    ->pipe($this->dataStore('salesQuarterProductNameNoAll'));
    
    $node2->pipe(new OnlyColumn(array(
      'productName', "{{all}}"
    )))->pipe($this->dataStore('salesQuarterProductNameAll'));
    
    $node->pipe(new Cube(array(
      "row" => "productLine",
      "column" => "orderQuarter",
      "sum" => "dollar_sales"
    )))
    ->pipe(new Sort(array(
      '{{all}}' => 'desc'
    )))
    ->pipe(new Limit(array(
      5, 0
    )))->pipe(new ColumnMeta(array(
      "{{all}}"=>array(
        "label"=>"Total",
      ),
      "productLine"=>array(
        "label"=>"Category",
      ),
    )))->saveTo($node2);
    
    $node2->pipe($this->dataStore('salesQuarterProductLine'));
    
    $node2->pipe(new RemoveColumn(array(
      "{{all}}"
    )))->pipe($this->dataStore('salesQuarterProductLineNoAll'));
    
    $node2->pipe(new OnlyColumn(array(
      'productLine', "{{all}}"
    )))->pipe($this->dataStore('salesQuarterProductLineAll'));
  }
}
