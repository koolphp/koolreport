<?php 

require_once "TableDemo.php";
$report = new TableDemo;
$report->run();
?>

<!DOCTYPE >
<html>
    <head>
        <title>Table Demo</title>
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