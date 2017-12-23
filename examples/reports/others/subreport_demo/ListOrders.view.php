<?php
    use \koolreport\widgets\koolphp\Table;
?>
<?php
    Table::create(array(
        "dataStore"=>isset($this->params["customerNumber"])?$this->dataStore("orders"):null,
        "data"=>isset($this->params["customerNumber"])?null:array(),
        "columns"=>array(
            "orderNumber"=>array(
                "label"=>"Order Number"
            ),
            "orderDate"=>array(
                "label"=>"Order Date",
            ),
            "status"=>array(
                "label"=>"Status",
            )
        )
    ));
?>