<?php
require_once "SalesCustomersProducts.php";
$SalesCustomersProducts = new SalesCustomersProducts;
?>
<!DOCTYPE>
<html>
  <head>
    <title>Sales By Customers and Categories</title>
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../../assets/css/example.css" />
  </head>
  <body>
    <div class="container box-container">
      <?php $SalesCustomersProducts->run()->render();?>    
    </div>
  </body>
</html>