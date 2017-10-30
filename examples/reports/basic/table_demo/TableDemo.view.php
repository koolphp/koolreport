<?php
use \koolreport\widgets\koolphp\Table;
?>

<div class="text-center">
    <h1>Table</h1>
    <h4>This page demonstrates the capability and usage of table</h4>
</div>
<hr/>

<h3>Table with minimum settings</h3>
<pre><code>
Table::create(array(
    "dataStore"=>$this->dataStore("data_sample"),
));
    </code></pre>
    <?php
    Table::create(array(
        "dataStore"=>$this->dataStore("data_sample"),
    ));
    ?>    


<h3>Column align</h3>
<pre><code>
Table::create(array(
    "dataStore"=>$this->dataStore("data_sample"),
    "columns"=>array(
        "item",
        "quantity",
        "price",
        "total"=>array(
            "cssStyle"=>"text-align:right"
        )
    )
));
    </code></pre>
<?php
    Table::create(array(
        "dataStore"=>$this->dataStore("data_sample"),
        "columns"=>array(
            "item",
            "quantity",
            "price",
            "total"=>array(
                "cssStyle"=>"text-align:right"
            )
        )
    ));
    ?>    


    <h3>Show Footer</h3>
    <pre><code>
Table::create(array(
    "dataStore"=>$this->dataStore("data_sample"),
    "showFooter"=>"bottom",
    "columns"=>array(
        "item",
        "quantity",
        "price",
        "total"=>array(
            "footer"=>"sum",
            "footerText"=>"<b>Total: @value</b>",
            "cssStyle"=>"text-align:right"
        )
    )
));    
    </code></pre>
    <?php
    Table::create(array(
        "dataStore"=>$this->dataStore("data_sample"),
        "showFooter"=>"bottom",
        "columns"=>array(
            "item",
            "quantity",
            "price",
            "total"=>array(
                "footer"=>"sum",
                "footerText"=>"<b>Total: @value</b>",
                "cssStyle"=>"text-align:right"
            )
        )
    ));    
    ?>
    <p>
    <i>Note:</i>The footer support following operation: <code>sum</code>, <code>avg</code>,<code>min</code>,<code>max</code>.
    </p>
    <h3>Footer on top</h3>
<pre><code>
Table::create(array(
        "dataStore"=>$this->dataStore("data_sample"),
        "showFooter"=>"top",
        "columns"=>array(
            "item",
            "quantity"=>array(
                "footer"=>"sum",
                "footerText"=>"<b>@value</b>"
            ),
            "price"=>array(
                "footer"=>"avg",
                "footerText"=>"<b>@value (avg)</b>"
            ),
            "total"=>array(
                "footer"=>"sum",
                "footerText"=>"<b>@value</b>"
            )
        )
    ));    

</code></pre>
    <?php
    Table::create(array(
        "dataStore"=>$this->dataStore("data_sample"),
        "showFooter"=>"top",
        "columns"=>array(
            "item",
            "quantity"=>array(
                "footer"=>"sum",
                "footerText"=>"<b>@value</b>"
            ),
            "price"=>array(
                "footer"=>"avg",
                "footerText"=>"<b>@value (avg)</b>"
            ),
            "total"=>array(
                "footer"=>"sum",
                "footerText"=>"<b>@value</b>"
            )
        )
    ));    
    ?>


    <h3>Off header</h3>
    <pre><code>
Table::create(array(
    "dataStore"=>$this->dataStore("data_sample"),
    "showHeader"=>false
));
    </code></pre>
    <?php
    Table::create(array(
        "dataStore"=>$this->dataStore("data_sample"),
        "showHeader"=>false,
    ));
    ?>    
    
    <h3>Format value manually</h3>
    <pre><code>
    Table::create(array(
        "dataStore"=>$this->dataStore("data_sample"),
        "columns"=>array(
            "item",
            "total"=>array(
                "formatValue"=>function($value)
                {
                    return (($value>1000)?"<i class='fa fa-thumbs-o-up text-success'></i>":"<i class='fa fa-thumbs-o-down text-danger'></i>").' $'.number_format($value);
                }
            )
        )
    ));    
    </code></pre>
    <?php
    Table::create(array(
        "dataStore"=>$this->dataStore("data_sample"),
        "columns"=>array(
            "item",
            "total"=>array(
                "formatValue"=>function($value)
                {
                    return (($value>1000)?"<i class='fa fa-thumbs-o-up text-success'></i>":"<i class='fa fa-thumbs-o-down text-danger'></i>").' $'.number_format($value);
                }
            )
        )
    ));
    ?>    

    <h3>Set css class</h3>
    <pre><code>
    Table::create(array(
        "dataStore"=>$this->dataStore("data_sample"),
        "cssClass"=>array(
            "th"=>"cssHeader",
            "tr"=>"cssItem"
        )
    ));
    </code></pre>
    <pre><code>
    &lt;style>
        .cssHeader
        {
            background-color:#e9ffe8;
        }
        .cssItem
        {
            background-color:#fdffe8;
        }
    &lt;/style>    
    </code></pre>    
    <?php
    Table::create(array(
        "dataStore"=>$this->dataStore("data_sample"),
        "cssClass"=>array(
            "th"=>"cssHeader",
            "tr"=>"cssItem"
        )
    ));
    ?>    
    <style>
        .cssHeader
        {
            background-color:#e9ffe8;
        }
        .cssItem
        {
            background-color:#fdffe8;
        }
    </style>
    <h3>Table paging</h3>
    <pre><code>
    Table::create(array(
        "dataStore"=>$this->dataStore("long_data"),
        "columns"=>array(
            "productName"=>array(
                "label"=>"Product Name"
            ),
            "productLine"=>array(
                "label"=>"Line"
            ),
            "quantityInStock"=>array(
                "label"=>"Qty In Stock"
            )
        ),
        "paging"=>array(
            "pageSize"=>10,
            "align"=>"center",
            "pageIndex"=>0,
        )
    ));
    </code></pre>    
    <?php
    Table::create(array(
        "dataStore"=>$this->dataStore("long_data"),
        "columns"=>array(
            "productName"=>array(
                "label"=>"Product Name"
            ),
            "productLine"=>array(
                "label"=>"Line"
            ),
            "quantityInStock"=>array(
                "label"=>"Qty In Stock"
            )
        ),
        "paging"=>array(
            "pageSize"=>10,
            "align"=>"center",
            "pageIndex"=>0,
        )
    ));
    ?>