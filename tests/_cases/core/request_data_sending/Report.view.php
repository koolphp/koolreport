<html>
    <head>
        <title>Test request data sending</title>
    </head>
    <body>
        <h1>Test Request Data Sending</h1>
        <p>Test whether data is sent only once to avoid data duplication</p>
        <?php
        \koolreport\widgets\koolphp\Table::create([
            "dataSource"=>$this->dataStore("data")
        ]);
        ?>
    </body>
</html>