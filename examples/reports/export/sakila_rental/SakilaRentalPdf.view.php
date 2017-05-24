<?php 
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\google\ColumnChart;
?>
<html>
    <body style="margin:0.5in 1in 0.5in 1in">
        <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.theme.min.css" />   
        <div class="page-header" style="text-align:right"><i>Sakila Rental Report</i></div>
        <div class="page-footer" style="text-align:right">{pageNum}</div>
        <div class="text-center">
            <h1>Cash In Report</h1>
            <h4>This report show the cash-in report per month</h4>
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
    </body>
</html>