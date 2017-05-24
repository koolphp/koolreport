<?php 
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\google\ColumnChart;
?>

<div class="text-center">
    <h1>Cash In Report</h1>
    <h4>This report show the cash-in report per month.<br/>The example uses PdoDatasource to connect to sakila database</h4>
</div>
<pre><code>
function settings()
{
    return array(
        "dataSources"=>array(
            "sakila_rental"=>array(
                "connectionString"=>"mysql:host=localhost;dbname=sakila",
                "username"=>"root",
                "password"=>"",
                "charset"=>"utf8"
            ),
        )
    ); 
}    
</code></pre>

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