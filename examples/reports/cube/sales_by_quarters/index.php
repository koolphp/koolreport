<?php
require_once "SalesQuarters.php";
$SalesQuarters = new SalesQuarters;
?>
<!DOCTYPE>
<html>
  <head>
    <title>Sales By Quarters</title>
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../../assets/css/example.css" />
  </head>
  <body>
    <div class="container box-container">
      <?php $SalesQuarters->run()->render();?>    
    </div>
  </body>
</html>