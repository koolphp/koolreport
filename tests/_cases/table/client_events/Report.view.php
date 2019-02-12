<?php
    use  \koolreport\widgets\koolphp\Table;
    $friends = array("Tuan","Dong");
?>

<html>
    <head>
        <title>Test Client Events</title>
    </head>
    <body>
        <h1>Test Table Client Events</h1>
        <p class="lead">Click on the rows, it should print the event opject at console.log</p>
        <?php
        Table::create(array(
            "dataSource"=>array(
                array("name","age"),
                array("Peter",35),
                array("David",36)
            ),
            "clientEvents"=>array(
                "rowClick"=>"function(e){
                    console.log(e);
                }"
            )
        ));
        ?>
    </body>
</html>