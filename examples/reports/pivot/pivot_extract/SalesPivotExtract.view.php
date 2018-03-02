<?php
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\pivot\widgets\PivotTable;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Extract Pivot Table</title>
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../../assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../../assets/css/example.css" />
  </head>
  <style>
    .box-container {
      width: 21cm;
    }
    .pivot-data-cell {
      text-align: right;
    }
    h1 {
      text-align: center;
    }
  </style>
  <body>
    <div class="container box-container">
          
      <h1>Sales</h1>
      <div style='text-align: center; margin: 10px'>
        <a href="export.php" class="btn btn-primary">Download PDF</a>
        <a href="exportExcel.php" class="btn btn-primary">Download Excel</a>
      </div>
      <div>
        <?php 
            echo '<b>Pivot Table:</b><br>';
            PivotTable::create(array(
                "dataStore"=>$this->dataStore('sales'),
                'rowCollapseLevels' => array(0),
                'columnCollapseLevels' => array(0),
            ));
            echo '<b>Extracted Tables:</b><br>';
            Table::create(array(
                "dataStore"=>$this->dataStore('salesTable1'),
            ));
            Table::create(array(
                "dataStore"=>$this->dataStore('salesTable2'),
            ));
        ?>
      </div>
      
    </div>
  </body>
</html>
