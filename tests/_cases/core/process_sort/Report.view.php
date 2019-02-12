<html>
    <head>
        <title>Test sorting data</title>
    </head>
    <body>
        <h1>Test Sorting data</h1>
        <?php
        \koolreport\widgets\koolphp\Table::create([
            "dataSource"=>$this->dataStore("data")
        ]);
        ?>
    </body>
</html>