<?php
    use \koolreport\datagrid\DataTables;
?>

<html>
    <head>
        <title>Test rowGroup</title>
    </head>
    <body>
        <h1>Test rowGroup</h1>

        <?php
        DataTables::create(array(
            "dataSource"=>$this->src("automaker")
            ->query("
                SELECT * from employees
            "),
            "options"=>array(
                "rowGroup"=>array(
                    "dataSrc"=> 7
                )
            )
        ));
        ?>
    </body>
</html>