<?php
    use \koolreport\drilldown\DrillDown;
    use \koolreport\widgets\google\ColumnChart;
    use \koolreport\widgets\koolphp\Table;
?>
<html>
    <head>
        <title>Advanced DrillDown</title>
    </head>
    <body>

    <?php
    DrillDown::create(array(
        "name"=>"mydrilldown",
        "title"=>"Sale By Location",
        "levels"=>array(
            array(
                "title"=>function($params,$scope)
                {
                    return "All Countries";
                },
                "widget"=>array(ColumnChart::class,array(
                    "dataSource"=>function($params,$scope)
                    {
                        return $this->src("automaker")->query("
                            SELECT country, sum(amount) as sale_amount
                            FROM
                                payments
                            JOIN
                                customers
                            ON
                                customers.customerNumber = payments.customerNumber
                            GROUP BY country
                        ");
                    },
                )),
                // "content"=>function($params,$scope)
                // {
                //     ColumnChart::create(array(
                //         "dataSource"=>$this->src("automaker")->query("
                //             SELECT country, sum(amount) as sale_amount
                //             FROM
                //                 payments
                //             JOIN
                //                 customers
                //             ON
                //                 customers.customerNumber = payments.customerNumber
                //             GROUP BY country
                //         "),
                //         "clientEvents"=>array(
                //             "itemSelect"=>"function(params){
                //                 mydrilldown.next({country:params.selectedRow[0]});
                //             }"
                //         )
                //     ));
                // }
            ),
            array(
                "title"=>function($params,$scope)
                {
                    return $params["country"];
                },
                "widget"=>array(Table::class,array(
                    "dataSource"=>function($params,$scope)
                    {
                        return $this->src("automaker")->query("
                            SELECT city, sum(amount) as sale_amount
                            FROM
                                payments
                            JOIN
                                customers
                            ON
                                customers.customerNumber = payments.customerNumber
                                AND
                                country=:country
                            GROUP BY 
                                city                        
                        ")->params(array(
                            ":country"=>$params["country"]
                        ));
                    }
                )),
                // "content"=>function($params,$scope)
                // {
                //     Table::create(array(
                //         "dataSource"=>$this->src("automaker")->query("
                //             SELECT city, sum(amount) as sale_amount
                //             FROM
                //                 payments
                //             JOIN
                //                 customers
                //             ON
                //                 customers.customerNumber = payments.customerNumber
                //                 AND
                //                 country=:country
                //             GROUP BY 
                //                 city                        
                //         ")->params(array(
                //             ":country"=>$params["country"]
                //         ))
                //     ));
                // },
            )
        )
    ))
    ?>
    </body>
</html>