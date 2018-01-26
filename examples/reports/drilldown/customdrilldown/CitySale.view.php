<level-title><?php echo $this->params["country"]; ?></level-title>
<?php
\koolreport\widgets\google\BarChart::create(array(
    "dataSource"=>$this->dataStore("city_sale"),
    "columns"=>array(
        "city","sale_amount"=>array(
            "label"=>"Sales(USD)",
            "prefix"=>'$',
        )
    ),
    "width"=>"100%",
));
?>