<?php 

require_once "SalesByCountry.php";
$report = new SalesByCountry;
$report->run();
?>

<!DOCTYPE >
<html>
    <head>
        <title>Sales By Country</title>
        <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="../../../assets/css/example.css" />
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCj8ahVQXoy8wHCAwoRWsUjPVmR5N3Qgko"
  type="text/javascript"></script>
    </head>
    <body>      
        <div class="container box-container">
            <?php $report->render();?>
        </div>
    </body>
</html>

