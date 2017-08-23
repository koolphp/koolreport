<?php
require_once '../../../../koolreport/autoload.php';
use \koolreport\processes\Filter;
use \koolreport\processes\ColumnMeta;
use \koolreport\pivot\processes\Pivot;

class CustomersCategoriesProducts extends koolreport\KoolReport
{
  function settings()
  {
    return array(
      "dataSources"=>array(
        'sales'=>array(
          'filePath' => '../../../databases/customer_product_dollarsales2.csv',
          'fieldSeparator' => ';',
          'class' => '\koolreport\datasources\CSVDataSource'      
        ),
      )
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
    ->pipe(new ColumnMeta(array(
      'dollar_sales'=>array(
        'type' => 'number',
        'prefix' => '$',
        'decimals'=>2,
      ),
    )))
    ->pipe(new Pivot(array(
      'dimensions'=>array(
        'row'=>'customerName, productLine, productName'
      ),
      'aggregates'=>array(
        'sum'=>'dollar_sales',
        'count'=>'dollar_sales'
      )
    )))
    ->pipe($this->dataStore('sales'));  
  }
}
