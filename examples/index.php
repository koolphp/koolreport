<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KoolReport Examples</title>
    <meta name="description" content="KoolReport, an intuitive and flexible open-source PHP reporting framework for faster and easier report delivery.">
    <meta name="author" content="KoolPHP Inc and KoolReport contributors">
    <meta name="keywords" content="php reporting framework,open-source reporting tools,reporting,php,data visualization,data analysis">    
    <meta name="msapplication-config" content="none"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/docs.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="assets/css/index.css" />

    <link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/site.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="assets/jquery/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/docs.min.js"></script>

</head>
<body>
    <div id="top" class="bs-docs-nav navbar-static-top"></div>
    <div class="bs-docs-header">
        <div class="container">
            <h1 id="basic-examples">Examples</h1>
            <p>KoolReport is an intuitive and flexible open-source PHP Reporting Framework for faster and easier report delivery.</p>
        </div>
    </div>
    
    <div class="container bs-docs-container">
        <div class="row">
            <div class="col-md-9">


                <div class="bs-docs-section">
                    <h1 id="basic">Basic</h1>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="example">
                                <h4>Sales By Customer</h4>
                                <p>Get the top ten customers who pay the most.</p>
                                <p><a class="btn btn-primary" href="reports/basic/sales_by_customer/index.php">View example</a></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="example">
                                <h4>Sakila Rental Report</h4>
                                <p>Get the sale report by month.</p>
                                <p><a class="btn btn-primary" href="reports/basic/sakila_rental/index.php">View example</a></p>
                            </div>
                        </div>                        
                    </div>            
                </div>
                
               
                <div class="bs-docs-section">
                    <h1 id="cube">Cube package</h1>
                    <p><code>Cube</code> package helps to turn your data into two dimension cross-tab table.
                        Get the <code>cube</code> package <a href="https://www.koolreport.com/packages/cube">in here</a>.
                    </p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="example">
                                <h4>Sales By Quarter</h4>
                                <p>Get sales by the quarters.</p>
                                
                                <p><a class="btn btn-primary" href="reports/cube/sales_by_quarters/index.php">View example</a></p>
                                <p><i>
                                    You may view this example
                                    <a href="https://www.koolreport.com/examples/reports/cube/sales_by_quarters/index.php">online</a>.
                                </i></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="example">
                                <h4>Sales By Months and Years</h4>
                                <p>Get sales by months and years.</p>
                                <p><a class="btn btn-primary" href="reports/cube/sales_by_months_years/index.php">View example</a></p>
                                <p><i>
                                    You may view this example
                                    <a href="https://www.koolreport.com/examples/reports/cube/sales_by_months_years/index.php">online</a>.
                                </i></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="example">
                                <h4>Sales By Customers And Products</h4>
                                <p>Get sales by customers and products.</p>
                                <p><a class="btn btn-primary" href="reports/cube/sales_by_customers_products/index.php">View example</a></p>
                                <p><i>
                                    You may view this example
                                    <a href="https://www.koolreport.com/examples/reports/cube/sales_by_customers_products/index.php">online</a>.
                                </i></p>
                            </div>
                        </div>
                        
                    </div>
                                
                </div> 


                <div class="bs-docs-section">
                    <h1 id="instruction">Instruction</h1>
                    <h2 id="setup-databases">Setup databases</h2>
                    <p>Most of our examples use <code>.csv</code> data file so that they can be run immediately
                        without any installation. However, in some examples we would like to demonstrate the real usage
                        of KoolReport with databases. So these examples require you to install samples databases.
                    </p>
                    <p>
                        You may find in the folder <code>./examples/databases</code> three(3) sample databases which are
                        <code>automaker</code>, <code>sakila</code> and <code>world</code>. They has been zipped for smaller size.
                    </p>
                    <p> <b>To install, you use <code>phpMyAdmin</code> to import directly those zipped file.</b>
                        There is no need to unzip.
                    </p>
                    <p>
                        <b>To set the databases authentication</b>, please open the <code>./examples/config.php</code> file, there you can set
                        your <code>username</code> and <code>password</code> so that example can work. The config file look like this:
                    </p>
                    <pre><code class="language-php" data-lang="php">
&lt;?php
return array(
    "automaker"=>array(
        "connectionString"=>"mysql:host=localhost;dbname=<b>automaker</b>",
        "username"=><b>"root"</b>,
        "password"=>"",
        "charset"=>"utf8"
    ),
    "sakila"=>array(
        "connectionString"=>"mysql:host=localhost;dbname=sakila",
        "username"=><b>"root"</b>,
        "password"=>"",
        "charset"=>"utf8"
    ),
    "world"=>array(
        "connectionString"=>"mysql:host=localhost;dbname=world",
        "username"=><b>"root"</b>,
        "password"=>"",
        "charset"=>"utf8"
    ),    
);               
                    </code></pre>
                    <h2 id="extended-packages">Extended packages</h2>
                    The demo may contains examples for specific packages which are not available standard KoolReport delivery.
                    To install a package, you need to:
                    <ol>
                        <li>Download the package from <a href="https://www.koolreport.com/packages">KoolReport packages</a></li>
                        <li>Unzip package and put it inside folder <code>"koolreport/packages"</code></li>
                        <li>All done! The packages is ready to use.</li>
                    </ol>
                </div>
            </div>
            <div class="col-md-3" role="complementary">
                <nav class="bs-docs-sidebar hidden-print hidden-sm hidden-xs affix-top">
                    <ul class="nav bs-docs-sidenav">
                        <li><a href="#basic">Basic</a></li>
                        <li><a href="#cube">Cube package</a></li>
                        <li><a href="#instruction">Instruction</a>
                            <ul class="nav">
                                <li><a href="#setup-databases">Setup databases</a></li>
                                <li><a href="#extended-packages">Extended packages</a></li>
                            </ul>
                        </li>
                    </ul>
                    <a href="#top" class="back-to-top"> Back to top </a>
                </nav>
            </div>
        </div>
    </div>    
    
    
    
    
    <div class="bs-docs-footer">
        <div class="container">
            <ul class="bs-docs-footer-links">
                <li><a href="https://www.koolreport.com" target="_blank">Home</a></li>
                <li><a href="https://www.facebook.com/groups/1643955672575545/" target="_blank">Discussion</a></li>                             
                <li><a href="https://www.koolreport.com/about">About</a></li>
            </ul>
            <p>Designed and built with <i class="fa fa-heart-o text-danger"></i> by <a href="https://www.koolphp.net">KoolPHP Inc</a>.</p>
            <p>Code licensed <a href="https://www.koolreport/license">MIT</a>.</p>
        </div>
    </div>    
</body>
</html>