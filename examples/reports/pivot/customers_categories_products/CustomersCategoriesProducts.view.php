<?php
use \koolreport\pivot\widgets\PivotTable;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Pivot Table of Customers and Categories - Products</title>
    <link rel='stylesheet' href='../../../assets/bootstrap/css/bootstrap.min.css'>
    <link rel='stylesheet' href='../../../assets/bootstrap/css/bootstrap-theme.min.css'>
    <link rel='stylesheet' href='../../../assets/font-awesome/css/font-awesome.min.css'>
    <link rel='stylesheet' href='../../../assets/css/example.css' />
  </head>
  <style>
    .box-container {
      width: 21cm;
    }
  </style>
  <body>
    <div class='container box-container'>
          
      <h1>Sales By Customers - Categories - Products</h1>
      <div>
        <?php
          $dataStore = $this->dataStore('sales');
          PivotTable::create(array(
            'dataStore'=>$dataStore,
            'rowDimension'=>'row',
            'measures'=>array(
              'dollar_sales - sum', 
              'dollar_sales - count',
            ),
            'rowSort' => array(
              'dollar_sales - sum' => 'desc',
            ),
            'rowCollapseLevels' => array(1),
            'totalName' => 'All',
            'width' => '100%',
            'nameMap' => array(
              'dollar_sales - sum' => 'Sales (in USD)',
              'dollar_sales - count' => 'Number of Sales',
            ),
          ));
        ?>
      </div>
      
    </div>
  </body>
</html>