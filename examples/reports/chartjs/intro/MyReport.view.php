<?php
    use \koolreport\chartjs\LineChart;
    use \koolreport\chartjs\AreaChart;
    use \koolreport\chartjs\BarChart;
    use \koolreport\chartjs\ColumnChart;
    use \koolreport\chartjs\PieChart;
    use \koolreport\chartjs\DonutChart;
    use \koolreport\chartjs\PolarChart;
    use \koolreport\chartjs\RadarChart;
    use \koolreport\chartjs\ScatterChart;
    use \koolreport\chartjs\BubbleChart;
    $category_amount = array(
        array("category"=>"Books","sale"=>32000,"cost"=>20000,"profit"=>12000),
        array("category"=>"Accessories","sale"=>43000,"cost"=>36000,"profit"=>7000),
        array("category"=>"Phones","sale"=>54000,"cost"=>39000,"profit"=>15000),
        array("category"=>"Movies","sale"=>23000,"cost"=>18000,"profit"=>5000),
        array("category"=>"Others","sale"=>12000,"cost"=>6000,"profit"=>6000),
    );
    $time_sale = array(
        array("month"=>"January","sale"=>32000,"cost"=>40000),
        array("month"=>"February","sale"=>48000,"cost"=>39000),
        array("month"=>"March","sale"=>35000,"cost"=>38000),
        array("month"=>"April","sale"=>40000,"cost"=>37000),
        array("month"=>"May","sale"=>60000,"cost"=>45000),
        array("month"=>"June","sale"=>73000,"cost"=>47000),
        array("month"=>"July","sale"=>80000,"cost"=>60000),
        array("month"=>"August","sale"=>78000,"cost"=>65000),
        array("month"=>"September","sale"=>60000,"cost"=>45000),
        array("month"=>"October","sale"=>83000,"cost"=>71000),
        array("month"=>"November","sale"=>45000,"cost"=>40000),
        array("month"=>"December","sale"=>39000,"cost"=>60000),
    );
    $category_sale_month = array(
        array("category"=>"Books","January"=>32000,"February"=>20000,"March"=>12000),
        array("category"=>"Accessories","January"=>43000,"February"=>36000,"March"=>7000),
        array("category"=>"Phones","January"=>54000,"February"=>39000,"March"=>15000),
        array("category"=>"Others","January"=>12000,"February"=>6000,"March"=>6000),
    );

    $height_weight_smokers = array(
        array("height","weight","smokers"),
        array(170,82,123),
        array(180,91,45),
        array(150,60,14),
        array(152,55,55),
        array(168,65,223),
        array(178,67,55),
        array(185,46,223),
        array(166,77,55),
        array(153,50,77),
        array(166,44,155),
    )
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Introduce ChartJS Data Visualazation</title>
        <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="../../../assets/css/example.css" />
    </head>
    <body>      
        <div class="container box-container">
            <div class="text-center">
                <h1>ChartJS</h1>
                <h4>
                This example shows type of charts in the package
                </h4>
            </div>
            <hr/>
            <div class="text-center"><h3>ColumnChart</h3></div>

            <?php
            ColumnChart::create(array(
                "title"=>"Sale Report",
                "dataSource"=>$category_amount,
                "columns"=>array(
                    "category",
                    "sale"=>array("label"=>"Sale","type"=>"number","prefix"=>"$"),
                    "cost"=>array("label"=>"Cost","type"=>"number","prefix"=>"$"),
                    "profit"=>array("label"=>"Profit","type"=>"number","prefix"=>"$"),
                )
            ));
            ?>
            <?php
            ColumnChart::create(array(
                "title"=>"Sale Report on Stack",
                "dataSource"=>$category_sale_month,
                "columns"=>array(
                    "category",
                    "January"=>array("label"=>"January","type"=>"number","prefix"=>"$"),
                    "February"=>array("label"=>"February","type"=>"number","prefix"=>"$"),
                    "March"=>array("label"=>"March","type"=>"number","prefix"=>"$"),
                ),
                "stacked"=>true
            ));
            ?>
            

            <div class="text-center"><h3>BarChart</h3></div>
            <?php
            BarChart::create(array(
                "title"=>"Sale Report",
                "dataSource"=>$category_amount,
                "columns"=>array(
                    "category",
                    "sale"=>array("label"=>"Sale","type"=>"number","prefix"=>"$"),
                    "cost"=>array("label"=>"Cost","type"=>"number","prefix"=>"$"),
                    "profit"=>array("label"=>"Profit","type"=>"number","prefix"=>"$"),
                )
            ));
            ?>
            <?php
            BarChart::create(array(
                "title"=>"Sale Report on Stack",
                "dataSource"=>$category_sale_month,
                "columns"=>array(
                    "category",
                    "January"=>array("label"=>"January","type"=>"number","prefix"=>"$"),
                    "February"=>array("label"=>"February","type"=>"number","prefix"=>"$"),
                    "March"=>array("label"=>"March","type"=>"number","prefix"=>"$"),
                ),
                "stacked"=>true
            ));
            ?>

            <div class="text-center"><h3>LineChart</h3></div>
            <?php
            LineChart::create(array(
                "title"=>"Sale vs Cost",
                "dataSource"=>$time_sale,
                "columns"=>array(
                    "month",
                    "sale"=>array(
                        "label"=>"Sale",
                        "type"=>"number",
                        "prefix"=>"$"
                    ),
                    "cost"=>array(
                        "label"=>"Cost",
                        "type"=>"number",
                        "prefix"=>"$"
                    ),
                )
            ));
            ?>
            <?php
            LineChart::create(array(
                "title"=>"Stepped Line Chart",
                "dataSource"=>$time_sale,
                "columns"=>array(
                    "month",
                    "sale"=>array(
                        "label"=>"Sale",
                        "type"=>"number",
                        "prefix"=>"$",
                        "config"=>array(
                            "steppedLine"=>true,
                        )
                    ),
                ),
            ));
            ?>



            <div class="text-center"><h3>AreaChart</h3></div>
            <?php
            AreaChart::create(array(
                "title"=>"Sale vs Cost",
                "dataSource"=>$time_sale,
                "columns"=>array(
                    "month",
                    "sale"=>array(
                        "label"=>"Sale",
                        "type"=>"number",
                        "prefix"=>"$"
                    ),
                    "cost"=>array(
                        "label"=>"Cost",
                        "type"=>"number",
                        "prefix"=>"$"
                    ),
                ),
                "backgroundOpacity"=>0.8
            ));
            ?>
            <div class="text-center"><h3>PieChart</h3></div>
            <?php
            PieChart::create(array(
                "title"=>"Sale Of Category",
                "dataSource"=>$category_amount,
                "columns"=>array(
                    "category",
                    "cost"=>array(
                        "type"=>"number",
                        "prefix"=>"$",
                    )
                )
            ));
            ?>

            <div class="text-center"><h3>DonutChart</h3></div>
            <?php
            DonutChart::create(array(
                "title"=>"Cost Of Category",
                "dataSource"=>$category_amount,
                "columns"=>array(
                    "category",
                    "cost"=>array(
                        "type"=>"number",
                        "prefix"=>"$",
                    )
                )
            ));
            ?>
            <div class="text-center"><h3>PolarChart</h3></div>
            <?php
            PolarChart::create(array(
                "title"=>"Cost Of Category",
                "dataSource"=>$category_amount,
                "columns"=>array("category","cost")
            ));
            ?>
            <div class="text-center"><h3>RadarChart</h3></div>
            <?php
            RadarChart::create(array(
                "title"=>"Sale distribution",
                "dataSource"=>$category_amount,
                "columns"=>array("category","sale")
            ));
            ?>

            <div class="text-center"><h3>ScatterChart</h3></div>
            <?php
            ScatterChart::create(array(
                "title"=>"Height vs Number of Smokers",
                "dataSource"=>$height_weight_smokers,
                "series"=>array(
                    array("height","smokers",array(
                        "label"=>"Height and Smokers"
                    )),
                )
            ));
            ?>

            <div class="text-center"><h3>BubbleChart</h3></div>
            <?php
            BubbleChart::create(array(
                "title"=>"Height, Weight vs Number of Smokers",
                "dataSource"=>$height_weight_smokers,
                "series"=>array(
                    array("height","weight","smokers",array(
                        "label"=>"Height, Weight and Smokers"
                    )),
                ),
                "scale"=>0.7
            ));
            ?>

            <div class="text-center"><h3>ComboChart</h3></div>
            <?php
            ColumnChart::create(array(
                "title"=>"Sale Report",
                "dataSource"=>$category_amount,
                "columns"=>array(
                    "category",
                    "sale"=>array(
                        "label"=>"Sale",
                        "type"=>"number",
                        "prefix"=>"$"
                    ),
                    "cost"=>array(
                        "label"=>"Cost",
                        "type"=>"number",
                        "prefix"=>"$"
                    ),
                    "profit"=>array(
                        "label"=>"Profit",
                        "type"=>"number",
                        "prefix"=>"$",
                        "config"=>array(
                            "type"=>"line",
                            "borderWidth"=>3,
                        )
                    ),
                ),
                "options"=>array(
                    "tooltips"=>array(
                        "mode"=>"index",
                        "intersect"=>true,
                    )
                )
            ));
            ?>
            

        </div>
    </body>
</html>
