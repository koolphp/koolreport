<?php
// error_reporting(E_ALL);
require_once "../../../../koolreport/autoload.php";
use \koolreport\processes\Filter;
use \koolreport\processes\ColumnMeta;
use \koolreport\processes\ValueMap;
use \koolreport\pivot\processes\Pivot;
use \koolreport\pivot\PivotExcelExport;
use \koolreport\pivot\processes\PivotExtract;

class SalesPivotExtract extends koolreport\KoolReport
{
  use \koolreport\excel\ExcelExportable;
  
  function settings()
  {
    return array(
      'assets' => array(
            'path' => '../../../assets',
            'url' => '../../../assets',
      ),
      "dataSources"=>array(
            "sales"=>array(
                'filePath' => '../../../databases/customer_product_dollarsales2.csv',
                'fieldSeparator' => ';',
                'class' => "\koolreport\datasources\CSVDataSource"      
            ),
      ),
    );
  }
  function setup()
  {
    $node = $this->src('sales');
    $node->pipe(new Filter(array(
      array('customerName', '<', 'Am'),
      array('orderYear', '>', 2003)
    )))
    ->pipe(new ColumnMeta(array(
      "dollar_sales"=>array(
        'type' => 'number',
        "prefix" => "$",
      ),
    )))
    ->pipe(new Pivot(array(
      "dimensions"=>array(
        "column"=>"orderYear, orderMonth",
        "row"=>"customerName, productLine, productName"
      ),
      "aggregates"=>array(
        "sum"=>"dollar_sales",
        "count"=>"dollar_sales"
      )
    )))->saveTo($node2);
    $node2->pipe($this->dataStore('sales')); 

    $node2->pipe(new PivotExtract(array(
      "row" => array(
          "parent" => array(),
      ),
      "column" => array(
          "parent" => array(
          ),
      ),
      "measures"=>array(
          "dollar_sales - sum", 
      ),
    )))
    ->pipe($this->dataStore('salesTable1'));

    $node2->pipe(new PivotExtract(array(
        "row" => array(
            "parent" => array(
                "customerName" => "AV Stores, Co."
            ),
            "sort" => array(
                'dollar_sales - sum' => 'desc',
            ),
        ),
        "column" => array(
            "parent" => array(
                "orderYear" => "2004"
            ),
            "sort" => array(
                'orderMonth' => function($a, $b) {
                    return (int)$a < (int)$b;
                },
            ),
        ),
        "measures"=>array(
            "dollar_sales - sum", 
            "dollar_sales - count", 
        ),
      )))
      ->pipe($this->dataStore('salesTable2'));
  }
}
