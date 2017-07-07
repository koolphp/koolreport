<?php 
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\google\BarChart;
?>

<div class="text-center">
    <h1>Sales Report</h1>
    <h4>This example show you how to format a column, the sale for each customer if greater $60,000 is good.</h4>
</div>
<hr/>

<?php
Table::create(array(
    "dataStore"=>$this->dataStore('sales_by_customer'),
        "columns"=>array(
            "customerName"=>array(
                "label"=>"Customer"
            ),
            "dollar_sales"=>array(
                "label"=>"Sales",
                "type"=>"number",
                "prefix"=>"$",
            ),
            "indicator"=>array(
                "label"=>"Greater than $60,000",
                "formatValue"=>function($value){
                    return ($value>60000)?"<i class='fa fa-check text-success'></i>":"";
                },
            )
        ),
    "cssClass"=>array(
        "table"=>"table table-hover table-bordered",
        "th"=>function($name){
            return $name=="indicator"?"text-center":"";
        },
        "td"=>function($value,$name){
            return $name=="indicator"?"text-center":"";
        }
    )
));
?>