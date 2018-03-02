<?php
require_once "SalesPivotExtract.php";
$report = new SalesPivotExtract();

$report->run()
->exportToExcel(array(
  "dataStores" => array(
    'sales' => array(
      'rowSort' => array(
        'dollar_sales - count' => 'desc',
      ),
      'headerMap' => function($v, $f) {
        if ($v === 'dollar_sales - sum')
          $v = 'Sales (in USD)';
        if ($v === 'dollar_sales - count')
          $v = 'Number of Sales';
        if ($f === 'orderYear')
          $v = 'Year ' . $v;
        return $v;
      },
    )
  ),
))
->toBrowser("SalesPivotExtract.xlsx");