<html>
    <head>
        <title>Customer Orders</title>
    </head>
    <body>
        <link rel="stylesheet" href="../../../assets/css/example.css" / >    
        <div class="container box-container">
            <div class="text-center">
                <h1>Customer Orders</h1>
                <h4>Select customer then display their orders</h4>
            </div>
            
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <?php $this->subReport("customerselecting"); ?>
                </div>
            </div>
            <hr/>
            
            <?php $this->subReport("listorders"); ?>
        </div>
    </body>
</html>