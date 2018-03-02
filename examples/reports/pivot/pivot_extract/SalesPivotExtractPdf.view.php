<?php
use \koolreport\pivot\widgets\PivotTable;
$document_root = str_replace("\\", "/", $_SERVER["DOCUMENT_ROOT"]);
$assets_folder = str_replace("\\", "/", realpath(dirname(__FILE__)."/../../../assets"));
$asset_url = str_replace($document_root,"",$assets_folder);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Pivot Table of Customers and Categories - Products</title>
    <link rel="stylesheet" href="<?php echo $asset_url; ?>/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $asset_url; ?>/bootstrap/css/bootstrap.theme.min.css" />
    <link rel="stylesheet" href="<?php echo $asset_url; ?>/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="<?php echo $asset_url; ?>/css/example.css" />
  </head>
  <style>
    .box-container {
      width: 29cm;
    }
    .pivot-data-cell {
      text-align: right;
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
            // "rowDimension"=>"row",
            // "columnDimension"=>"column",
            "measures"=>array(
              "dollar_sales - sum", 
              // 'dollar_sales - count',
            ),
            'rowSort' => array(
              // 'orderMonth' => function($a, $b) {
                // return (int)$a > (int)$b;
              // }, 
              // 'orderDay' => function($a, $b) {
                // return (int)$a > (int)$b;
              // },
              'dollar_sales - sum' => 'desc',
            ),
            'columnSort' => array(
              'orderMonth' => function($a, $b) {
                return (int)$a < (int)$b;
              },
              // 'dollar_sales - sum' => 'desc',
              // 'orderYear' => 'desc', 
            ),
            // 'rowCollapseLevels' => array(1),
            // 'columnCollapseLevels' => array(0),
            'width' => '100%',
            'headerMap' => array(
              'dollar_sales - sum' => 'Sales (in USD)',
              'dollar_sales - count' => 'Number of Sales',
            ),
          ));
        ?>
      </div>
      
    </div>
  </body>
</html>
