<?php
use \koolreport\pivot\widgets\PivotTable;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Pivot Table of Customers and Categories - Products</title>
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../../assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../../assets/css/example.css" />
  </head>
  <style>
    .box-container {
      width: 21cm;
    }
  </style>
  <body>
    <div class="container box-container">
          
      <h1>Sales By Years - Months - Customers - Categories</h1>
      <div>
        <?php
          $dataStore = $this->dataStore('sales');
          PivotTable::create(array(
            "dataStore"=>$dataStore,
            "rowDimension"=>"row",
            "columnDimension"=>"column",
            "measures"=>array(
              "dollar_sales - sum", 
              // 'dollar_sales - count',
            ),
            'rowSort' => array(
              'dollar_sales - count' => 'desc',
            ),
            'columnSort' => array(
              'orderMonth' => function($a, $b) {
                return (int)$a < (int)$b;
              },
            ),
            'rowCollapseLevels' => array(0),
            'columnCollapseLevels' => array(0),
            'width' => '100%',
            'nameMap' => array(
              'dollar_sales - sum' => 'Sales (in USD)',
              'dollar_sales - count' => 'Number of Sales',
              '1' => 'January',
              '2' => 'February',
              '3' => 'March',
              '4' => 'April',
              '5' => 'May',
              '6' => 'June',
              '7' => 'July',
              '8' => 'August',
              '9' => 'September',
              '10' => 'October',
              '11' => 'November',
              '12' => 'December',
            ),
          ));
        ?>
      </div>
      
    </div>
  </body>
</html>
