<?php 
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\inputs\DateRangePicker;
    use \koolreport\inputs\MultiSelect;
?>

<!DOCTYPE >
<html>
    <head>
        <title>Input Control</title>
    </head>
    <body>      
        <link rel="stylesheet" href="../../../assets/css/example.css" />
        <div class="container box-container">
            <div class="text-center">
                <h1>List of order</h1>
                <h4>Choose date ranges and customer to view orders</h4>
            </div>
            <form method="post">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="form-group">
                        <?php
                        DateRangePicker::create(array(
                            "name"=>"dateRange"
                        ))
                        ?>
                        </div>
                        <div class="form-group">
                        <?php
                        MultiSelect::create(array(
                            "name"=>"customers",
                            "dataStore"=>$this->dataStore("customers"),
                            "dataBind"=>array(
                                "text"=>"customerName",
                                "value"=>"customerNumber",
                            ),
                            "attributes"=>array(
                                "class"=>"form-control",
                                "size"=>10,
                            )
                        ));
                        ?>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-success"><i class="glyphicon glyphicon-refresh"></i> Load</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr/>
            <?php
            if($this->dataStore("result")->countData()>0)
            {
                Table::create(array(
                    "dataStore"=>$this->dataStore("result"),
                    "removeDuplicate"=>array("customerName","orderNumber"),
                    "columns"=>array(
                        "customerName"=>array(
                            "label"=>"Customer",
                        ),
                        "orderNumber"=>array(
                            "label"=>"#Order",
                            "type"=>"string",
                        ),
                        "productName"=>array(
                            "label"=>"Product"
                        ),
                        "amount"=>array(
                            "label"=>"Amount",
                            "prefix"=>"$",
                        ),
                        "status"=>array(
                            "label"=>"Status",
                        )

                    )
                ));
            }
            else
            {
            ?>
                <div class="alert alert-warning">
                    <i class="glyphicon glyphicon-info-sign"></i> Sorry, we found no orders found
                </div>
            <?php    
            }
            ?>
        </div>
    </body>
</html>
