<?php 
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\google\ColumnChart;
?>

<div class="text-center">
    <h1>Cash In Report</h1>
    <h4>This example show how to export report to PDF</h4>
    <a href="export.php" class="btn btn-primary">Download PDF</a>
</div>
<hr/>

<?php
ColumnChart::create(array(
    "dataStore"=>$this->dataStore('sale_by_month'),  
    "columns"=>array(
        "payment_date"=>array(
            "label"=>"Month",
            "type"=>"datetime",
            "format"=>"Y-n",
            "displayFormat"=>"F, Y",
        ),
        "amount"=>array(
            "label"=>"Amount",
            "type"=>"number",
            "prefix"=>"$",
        )
    ),
    "width"=>"100%",
));
?>

<?php
Table::create(array(
    "dataStore"=>$this->dataStore('sale_by_month'),
    "columns"=>array(
        "payment_date"=>array(
            "label"=>"Month",
            "type"=>"datetime",
            "format"=>"Y-n",
            "displayFormat"=>"F, Y",
        ),
        "amount"=>array(
            "label"=>"Amount",
            "type"=>"number",
            "prefix"=>"$",
                    )
    ),
    "cssClass"=>array(
        "table"=>"table table-hover table-bordered"
    )
));
?>