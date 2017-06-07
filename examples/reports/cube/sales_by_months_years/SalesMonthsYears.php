<?php
require_once "../../../../koolreport/autoload.php";
use \koolreport\processes\ColumnMeta;
use \koolreport\processes\Limit;
use \koolreport\processes\RemoveColumn;
use \koolreport\processes\OnlyColumn;
use \koolreport\processes\Sort;
use \koolreport\processes\ColumnsSort;
use \koolreport\processes\ValueMap;
use \koolreport\cube\processes\Cube;

class SalesMonthsYears extends koolreport\KoolReport
{
  function settings()
  {
    return array(
      "dataSources"=>array(
        "sales"=>array(
          'connectionString' => 'mysql:host=localhost;dbname=automaker',
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',          
        ),
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
        "prefix" => "$",
      ),
    )));
    
    $node->pipe(new Cube(array(
      "column" => "orderYear",
      "sum" => "dollar_sales"
    )))->pipe(new ColumnMeta(array(
      "{{all}}"=>array(
        "label"=>"Total",
      ),
      "orderMonth"=>array(
        "label"=>"Month",
      ),
    )))->saveTo($node2);
    $node2->pipe($this->dataStore('salesYear'));
    $node2->pipe(new RemoveColumn(array(
      "{{all}}"
    )))->pipe($this->dataStore('salesYearNoAll'));
    
    $node->pipe(new Cube(array(
      "row" => "orderMonth",
      "column" => "orderYear",
      "sum" => "dollar_sales"
    )))
    ->pipe(new ColumnMeta(array(
      "{{all}}"=>array(
        "label"=>"Total",
      ),
      "orderMonth"=>array(
        "label"=>"Month",
    ))))
    ->pipe(new Sort(array(
      'orderMonth' => 'asc'
    )))
    ->pipe(new ColumnsSort(array(
      '{name}' => 'asc',
      'fixedColumns' => array(
        0 => 0,
        '{{all}}' => 10
      ),
    )))
    ->pipe(new ValueMap(array(
      "orderMonth"=>array(
        1 => "January",
        2 => "February",
        3 => "March",
        4 => "April",
        5 => "May",
        6 => "June",
        7 => "July",
        8 => "August",
        9 => "September",
        10 => "October",
        11 => "November",
        12 => "December",
    ))))
    ->saveTo($node2);
    $node2->pipe($this->dataStore('salesYearMonth'));
    $node2->pipe(new RemoveColumn(array(
      "{{all}}"
    )))->pipe($this->dataStore('salesYearMonthNoAll'));
    
    $node->pipe(new Cube(array(
      "row" => "productLine",
      "column" => "orderYear",
      "sum" => "dollar_sales"
    )))->pipe(new ColumnMeta(array(
      "{{all}}"=>array(
        "label"=>"Total",
      ),
      "orderYear"=>array(
        "label"=>"Year",
      ),
    )))->saveTo($node2);   
    $node2->pipe($this->dataStore('salesYearCategory'));
    $node2->pipe(new RemoveColumn(array(
      "{{all}}"
    )))->pipe($this->dataStore('salesYearCategoryNoAll'));
    $node2->pipe(new OnlyColumn(array(
      'productLine', "{{all}}"
    )))->pipe($this->dataStore('salesYearCategoryAll'));
  }
}
