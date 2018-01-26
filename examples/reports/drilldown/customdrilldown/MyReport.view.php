<!DOCTYPE html>
<html>
    <head>
        <title>Sale By Location Using CustomDrillDown</title>
        <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="../../../assets/css/example.css" />
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCj8ahVQXoy8wHCAwoRWsUjPVmR5N3Qgko"
  type="text/javascript"></script>
    </head>
    <body>      
        <div class="container box-container">
            <div class="text-center">
                <h1>Sale By Location</h1>
                <h4>
                This example shows how to setup a <code>CustomDrillDown</code> report to see sale report
                by location.
                </h4>
                <p>Please click on the column of chart to go further down on details.</p>
            </div>
            <hr/>


            <?php
                \koolreport\drilldown\CustomDrillDown::create(array(
                    "name"=>"saleLocation",
                    "title"=>"Sale By Location",
                    "subReports"=>array("countrySale","citySale"),
                ));
            ?> 
        </div>
    </body>
</html>
