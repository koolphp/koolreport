<?php 

require_once "SakilaRental.php";
$report = new SakilaRental;
$report->run();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Sakila Rental</title>
        <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="../../../assets/css/example.css" />
    </head>
    <body>      
        <div class="container box-container">
            <?php $report->render();?>
        </div>
    </body>
</html>

