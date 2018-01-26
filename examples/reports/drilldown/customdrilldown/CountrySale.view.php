<?php
    $drilldown = $this->params["@drilldown"];
?>
<level-title>All countries</level-title>
<?php
    \koolreport\widgets\google\GeoChart::create(array(
        "dataSource"=>$this->dataStore("country_sale"),
        "columns"=>array("country","sale_amount"=>array(
            "label"=>"Sales(USD)",
            "prefix"=>'$',
        )),
        "clientEvents"=>array(
            "rowSelect"=>"function(params){
                $drilldown.next({country:params.selectedRow[0]});
            }",
        ),
        "width"=>"100%",
    ));
?>