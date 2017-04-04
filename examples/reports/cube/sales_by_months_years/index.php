<?php
require_once "SalesMonthsYears.php";
$SalesMonthsYears = new SalesMonthsYears;
?>
<!DOCTYPE>
<html>
  <head>
    <title>Sales By Years</title>
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../../assets/css/example.css" />
  </head>
  <body>
    <div class="container box-container">
      <?php $SalesMonthsYears->run()->render();?>    
    </div>
  </body>
</html>