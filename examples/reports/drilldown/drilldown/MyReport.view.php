<?php
    use \koolreport\drilldown\DrillDown;
    use \koolreport\processes\CopyColumn;
    use \koolreport\processes\DateTimeFormat;
    use \koolreport\widgets\google\ColumnChart;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Sale By Time Using DrillDown</title>
        <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="../../../assets/css/example.css" />
    </head>
    <body>      
        <div class="container box-container">
            <div class="text-center">
                <h1>Sale By Time</h1>
                <h4>
                This example shows how to setup a drill down report to see sale report
                by time.
                </h4>
                <p>Please click on the column of chart to go further down on details.</p>
            </div>
            <hr/>


            <?php
            DrillDown::create(array(
                "name"=>"saleDrillDown",
                "title"=>"Sale Report",
                "btnBack"=>true,
                "dataSource"=>(
                    $this->src('automaker')
                    ->query("SELECT amount, paymentDate FROM payments")
                    ->pipe(new CopyColumn(array(
                        "year"=>"paymentDate",
                        "month"=>"paymentDate",
                        "day"=>"paymentDate",
                    )))
                    ->pipe(new DateTimeFormat(array(
                        "year"=>"Y",
                        "month"=>"m",
                        "day"=>"d",
                    )))
                ),
                "calculate"=>array(
                    "sum"=>"amount"
                ),
                "levels"=>array(
                    array(
                        "groupBy"=>"year",
                        "widget"=>array(ColumnChart::class,array(
                            "columns"=>array("year","amount"=>array(
                                "label"=>"Amount",
                                "prefix"=>'$'
                            )),
                            "colorScheme"=>array("#3b9b00"),
                        )),
                        "title"=>"All Years",
                    ),
                    array(
                        "groupBy"=>"month",
                        "widget"=>array(ColumnChart::class,array(
                            "columns"=>array("month","amount"=>array(
                                "label"=>"Amount",
                                "prefix"=>'$'
                            )),
                            "colorScheme"=>array("#af17b5"),
                        )),
                        "title"=>function($params)
                        {
                            return "Year ".$params["year"];
                        },
                    ),
                    array(
                        "groupBy"=>"day",
                        "widget"=>array(ColumnChart::class,array(
                            "columns"=>array("day","amount"=>array(
                                "label"=>"Amount",
                                "prefix"=>'$'
                            )),
                            "colorScheme"=>array("#e0dc00"),
                        )),
                        "title"=>function($params)
                        {
                            return date('F', mktime(0, 0, 0, $params["month"], 10));
                        },
                    ),
                ),
            ));
            ?> 
        </div>
    </body>
</html>
