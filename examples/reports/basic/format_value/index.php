<?php 

require_once "SalesByCustomer.php";
$salesbycustomer = new SalesByCustomer;
$salesbycustomer->run();
?>

<!DOCTYPE >
<html>
  <head>
    <title>Format Value</title>
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="../../../assets/css/example.css" />
  </head>
  <body>    
    <div class="container box-container">
      <?php $salesbycustomer->render();?>
    </div>
  </body>
</html>

