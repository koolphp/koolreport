<?php
    use \koolreport\widgets\google\ColumnChart;
    use \koolreport\widgets\google\BarChart;
    use \koolreport\widgets\google\Gauge;
    use \koolreport\widgets\google\Timeline;
    $category_amount = array(
        array("category"=>"Books","sale"=>32000,"cost"=>20000,"profit"=>12000),
        array("category"=>"Accessories","sale"=>43000,"cost"=>36000,"profit"=>7000),
        array("category"=>"Phones","sale"=>54000,"cost"=>39000,"profit"=>15000),
        array("category"=>"Movies","sale"=>23000,"cost"=>18000,"profit"=>5000),
        array("category"=>"Others","sale"=>12000,"cost"=>6000,"profit"=>6000),
    );
?>
<html>
    <head>
        <title>Test multiple google charts</title>
    </head>
    <body>
        <h1>Test multiple google charts</h1>
        <p class="lead">
        There was issue that multiple google charts which are different in packages and stability
        loaded in a sample.
        Only the previous charts works but the rest.
        </p>

        <h2>BarChart</h2>
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

        <h2>BarChart</h2>
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


        <h2>Now Gauge Chart</h2>

        <?php
        Gauge::create(array(
            "title"=>"Sale Report on Stack",
            "dataSource"=>array(
                array("label"=>"Memory","value"=>80),
                array("label"=>"CPU","value"=>55),
                array("label"=>"Nework","value"=>68),
            ),
            "columns"=>array(
                "label",
                "value"=>array(
                    "suffix"=>"%",
                )
            )
        ));
        ?>

        <?php
        Timeline::create(array(
            "dataSource"=>[
                ['President','Start','End'],
                [ 'Gerald Ford',  "1974-01-20",  "1977-01-20" ],
                [ 'Jimmy Carter',  "1977-01-20",  "1981-01-20" ],
                [ 'Ronald Reagan',  "1981-01-20",  "1989-01-20" ],
                [ 'George H. W. Bush',  "1989-01-20",  "1993-01-20" ],
                [ 'Bill Clinton',  "1993-01-20",  "2001-01-20" ],
                [ 'George W. Bush',  "2001-01-20",  "2009-01-20" ],
                [ 'Barack Obama',  "2009-01-20",  "2017-01-20" ],
                [ 'Donald Trump',  "2017-01-20",  date("Y-m-d") ],
            ],
            "columns"=>array(
                "President",
                "Start"=>array(
                    "type"=>"date",
                ),
                "End"=>array(
                    "type"=>"date",
                )
            )
        ));
        ?>

    </body>
</html>