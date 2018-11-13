<?php
    use \koolreport\datagrid\DataTables;
?>

<html>
    <head>
        <title>Test Language in DataTables</title>
    </head>
    <body>
        <h1>Test Language in DataTables</h1>

        <?php
        DataTables::create(array(
            "language"=>"de",
            "dataSource"=>array(
                array("name"=>"Peter","age"=>35),
                array("name"=>"David","age"=>36),
            ),
            "options"=>array(
                "paging"=>true,
                'colReorder'=>true,
            )
        ));
        ?>
    </body>
</html>