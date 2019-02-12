<?php
    use \koolreport\widgets\koolphp\Table;
?>
<div class="report-content">
    <div class="text-center">
        <h1>Table Row Group</h1>
        <p class="lead">
            This example shows how to setup row grouping with Table widgets
        </p>
    </div>

    <?php
    Table::create(array(
        "dataSource"=>$this->dataStore('payments'),
        "grouping"=>array(
            "year"=>array(
                "calculate"=>array(
                    "{sumAmount}"=>array("sum","amount")
                ),
                "sort"=>"desc",
                "top"=>"<b>Year {year}</b>",
                "bottom"=>"<td><b>Total of year {year}</b></td><td><b>{sumAmount}</b></td>"
            ),
        ),
        "showFooter"=>true,
        "sorting"=>array(
            "month"=>"asc",
        ),
        "columns"=>array(
            "month"=>array(
                "label"=>"Month",
                "footerText"=>"<b>Grand Totals</b>"
            ),
            "amount"=>array(
                "label"=>"Sale Amount",
                "prefix"=>"$",
                "footer"=>"sum",
                "footerText"=>"<b>@value</b>"
            )
        ),
        "cssClass"=>array(
            "table"=>"table-bordered",
            "tf"=>"darker"
        )
    ));
    ?>

    <h2>Now</h2>
    <?php
    Table::create(array(
        "dataSource"=>$this->dataStore("payments"),
        "sorting"=>array(
            "year"=>"desc",
            "month"=>"asc",
        )
    ));
    ?>

    <style>
        .darker
        {
            background:#ccc;
        }
    </style>
</div>