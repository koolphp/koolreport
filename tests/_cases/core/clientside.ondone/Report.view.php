<?php
    use \koolreport\widgets\koolphp\Table;
?>
<html>
    <head>
        <title>Test</title>
    </head>
    <body>
        <h1>Test</h1>
        <div id="test"></div>
        <?php
        Table::create(array(
            "name"=>"mytable",
            "dataSource"=>array(
                array("name"=>"Peter","age"=>35),
                array("name"=>"David","age"=>36),
            )
        ));
        ?>

        <script>
        KoolReport.load.onDone(function(){
            document.getElementById("test").innerText = "run";
        });
        </script>
    </body>
</html>