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
    <script type="text/javascript" src="assets/jquery/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/docs.min.js"></script>

</head>
<body>
    <div id="top" class="bs-docs-nav navbar-static-top"></div>

<nav id="top" class="navbar bs-docs-nav navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          
          <a class="navbar-brand" href="https://www.koolreport.com/"><i class="fa fa-bar-chart" style="color:#F9A825"></i> <span style="color:#0277BD">KoolReport</span></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li id="getstarted"><a href="https://www.koolreport.com/getting-started"><i class="fa fa-bolt"></i> Getting Started</a></li>
            <li id="demo"><a href="https://www.koolreport.com/examples">Online Demos</a></li>  
          	<li class="dropdown">
          		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Documentation <span class="caret"></span></a>
          		 <ul class="dropdown-menu">
                    <li id="datasources"><a href="https://www.koolreport.com/data-sources">Data Sources</a></li>
                    <li id="processing"><a href="https://www.koolreport.com/data-processing">Data Processing</a></li>
                    <li id="visualization"><a href="https://www.koolreport.com/data-visualization">Data Visualization</a></li>
          		 	    <li id="class-reference"><a href="https://www.koolreport.com/class-reference">Class Reference</a></li>
                     <li id="change-log"><a href="https://www.koolreport.com/updates">Updates</a></li>
          		 </ul>
          	</li>
          	<li id="packages"><a href="https://www.koolreport.com/packages">Packages</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
          	<li id="forum"><a href="https://www.koolreport.com/forum/topics"><i class="fa fa-comments-o"></i> Forum</a></li>
            <li id="blog"><a href="https://www.koolreport.com/blog"><i class="fa fa-feed"></i> Blog</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

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
                        <div class="col-md-4">
                            <div class="example">
                                <h4>Sakila Rental Report With Database</h4>
                                <p>Connect to Sakila database using PdoDataSource</p>
                                <p><a class="btn btn-primary" href="reports/basic/database_connection/index.php">View example</a></p>
                            </div>
                        </div>   
                        <div class="col-md-4">
                            <div class="example">
                                <h4>Format value in column</h4>
                                <p>Show how to format value in table's column</p>
                                <p><a class="btn btn-primary" href="reports/basic/format_value/index.php">View example</a></p>
                            </div>
                        </div>                                                                     
                        <div class="col-md-4">
                            <div class="example">
                                <h4>Sale By Country with GeoChart</h4>
                                <p>Use Geochart in report</p>
                                <p><a class="btn btn-primary" href="reports/basic/sales_by_country/index.php">View example</a></p>
                            </div>
                        </div>                                                                     
                    </div>            
                </div>
                <div class="bs-docs-section">
                    <h1 id="advanced">Advanced examples</h1>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="example">
                                <h4>Order List</h4>
                                <p>View order of a customer, export report</p>
                                <p><a class="btn btn-primary" href="https://www.koolreport.com/examples/reports/advanced/input_and_export/index.php">View example</a></p>
                            </div>
                        </div>                                                                                            
                    </div>
                </div>

                <div class="bs-docs-section">
                    <h1 id="export">Export package</h1>
                    <p><code>Export</code> package helps to export your report to PDF and other formats.
                        Get the <code>Export</code> package <a href="https://www.koolreport.com/packages/export">in here</a>.
                    </p>                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="example">
                                <h4>Export Sakila Rental Report</h4>
                                <p>Export report to PDF</p>
                                <p><a class="btn btn-primary" href="reports/export/sakila_rental/index.php">View example</a></p>
                                <p><i>
                                    You may view this example
                                    <a href="https://www.koolreport.com/examples/reports/export/sakila_rental/index.php">online</a>.
                                </i></p>
                            </div>
                        </div>
                    </div>            
                </div>


                <div class="bs-docs-section">
                    <h1 id="cube">Cube package</h1>
                    <p><code>Cube</code> package helps to turn your data into two dimension cross-tab table.
                        Get the <code>Cube</code> package <a href="https://www.koolreport.com/packages/cube">in here</a>.
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
                    <h1 id="pivot">Pivot package</h1>
                    <p><code>Pivot</code> package helps you to explore multi-dimension aggregated data in a hierarchical way.
                        Get the <code>Pivot</code> package <a href="https://www.koolreport.com/packages/pivot">in here</a>.
                    </p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="example">
                                <h4>Customers - Categories - Products Pivot</h4>
                                <p>Get pivot table for customers, categories and products.</p>
                                <p><a class="btn btn-primary" href="reports/pivot/customers_categories_products/index.php">View example</a></p>
                                <p><i>
                                    You may view this example
                                    <a href="https://www.koolreport.com/examples/reports/pivot/customers_categories_products/index.php">online</a>.
                                </i></p>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="example">
                                <h4>Years - Months Pivot</h4>
                                <p>Get pivot table for years and months.</p>
                                <p><a class="btn btn-primary" href="reports/pivot/years_months/index.php">View example</a></p>
                                <p><i>
                                    You may view this example
                                    <a href="https://www.koolreport.com/examples/reports/pivot/years_months/index.php">online</a>.
                                </i></p>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="example">
                                <h4>Years -Months - Customers - Categories Pivot</h4>
                                <p>Get pivot table for years, months, customers and categories.</p>
                                <p><a class="btn btn-primary" href="reports/pivot/years_months_customers_categories/index.php">View example</a></p>
                                <p><i>
                                    You may view this example
                                    <a href="https://www.koolreport.com/examples/reports/pivot/years_months_customers_categories/index.php">online</a>.
                                </i></p>
                            </div>
                        </div>
                        
                    </div>            
                </div>
                <div class="bs-docs-section">
                    <h1 id="inputs">Inputs package</h1>
                    <p><code>Inputs</code> package helps you to build dynamic reports with user's inputs
                        Get the <code>Inputs</code> package <a href="https://www.koolreport.com/packages/inputs">in here</a>. It's FREE.
                    </p>
                    <div class="row">
                        <div class="col-md-4">                        
                            <div class="example">
                                <h4>Introduce controls</h4>
                                <p>List of all current controls in inputs </p>
                                <p><a class="btn btn-primary" href="reports/inputs/intro/index.php">View example</a></p>
                                <p><i>
                                    You may view this example
                                    <a href="https://www.koolreport.com/examples/reports/inputs/intro/index.php">online</a>.
                                </i></p>
                            </div>
                        </div>                        
                        <div class="col-md-4">                        
                            <div class="example">
                                <h4>List of orders</h4>
                                <p>Get list of orders within date ranges and of selected customers</p>
                                <p><a class="btn btn-primary" href="reports/inputs/order_list/index.php">View example</a></p>
                                <p><i>
                                    You may view this example
                                    <a href="https://www.koolreport.com/examples/reports/inputs/order_list/index.php">online</a>.
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
                        <li><a href="#advanced">Advanced</a></li>
                        <li><a href="#export">Export package</a></li>
                        <li><a href="#cube">Cube package</a></li>
                        <li><a href="#pivot">Pivot package</a></li>
                        <li><a href="#inputs">Input package</a></li>
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