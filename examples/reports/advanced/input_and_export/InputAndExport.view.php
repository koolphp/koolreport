<?php
    use \koolreport\inputs\Select;
    use \koolreport\widgets\koolphp\Table;

    $customerName = "";
    $this->dataStore("customers")->popStart();
    while($row = $this->dataStore("customers")->pop())
    {
        if($row["customerNumber"]==$this->params["customerNumber"])
        {
            $customerName =$row["customerName"];
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Order details of customer</title>
    </head>
    <body>
    <link rel="stylesheet" href="../../../assets/css/example.css" />
        <div class="container box-container">
            <form method="post">
                <div class="text-center">
                    <h1>List order of a customer</h1>
                    <div class="row form-group">
                        <div class="col-md-6 col-md-offset-3">
                            <?php
                            Select::create(array(
                                "name"=>"customerNumber",
                                "dataStore"=>$this->dataStore("customers"),
                                "dataBind"=>array(
                                    "text"=>"customerName",
                                    "value"=>"customerNumber",
                                ),
                                "attributes"=>array(
                                    "class"=>"form-control"
                                )
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Look up</button>
                    </div>
                </div>
            </form>
            <hr/>
            <?php
                if($this->dataStore("orders")->countData()>0)
                {
                ?>
                    <?php
                    Table::create(array(
                        "dataStore"=>$this->dataStore("orders"),
                        "columns"=>array(
                            "productName"=>array(
                                "label"=>"Product",
                            ),
                            "priceEach"=>array(
                                "label"=>"Price",
                                "prefix"=>"$",
                            ),
                            "quantityOrdered"=>array(
                                "label"=>"Quantity"
                            ),
                            "amount"=>array(
                                "label"=>"Total",
                                "prefix"=>"$",
                            )
                        ),
                        "class"=>array(
                            "table"=>"table table-striped"
                        )
                    ));
                    ?>
                    <div class="text-center">
                        <form method="post" action="export.php">
                            <input type="hidden" value="<?php echo $this->params["customerNumber"]; ?>" name="customerNumber" />
                            <button class="btn btn-primary">Export to PDF</button>
                        </form>
                    </div>
                <?php
                }
            ?>
        </div>
    </body>
</html>