<?php
    use \koolreport\datagrid\DataTables;

?>

<html>
    <head>
        <title>Test Sorting Number in DataTables</title>
    </head>
    <body>
        <h1>Test Sorting Number in DataTables</h1>


        <?php
        DataTables::create(array(
            "dataSource"=>$this->dataStore("data"),
            "showFooter"=>true,
            "columns"=>array(
                "col1",
                "num"=>array(
                    "type"=>"number",
                    "formatValue"=>function($value){
                        $pad = str_pad(number_format($value,0,"",""),11,"0",STR_PAD_LEFT);
                        return "<span style='display:none'>$pad</span>".number_format($value,2,",",".");
                    },
                    "footer"=>"sum"
                )
            ),
            "options"=>array(
                "columnDefs"=>array(
                    array("type"=>"string","targets"=>1,"className"=>"dt-body-right"),
                ),
            )
        ));
        var_dump($this->dataStore("data")->pluck('num'));
        ?>
    </body>
</html>