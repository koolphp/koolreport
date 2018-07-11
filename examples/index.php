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
<style type="text/css">
    a.pk
    {
        color:#333;
        text-decoration:none;
    }
    div.thumbnail:hover
    {
        box-shadow: 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12), 0 3px 5px -1px rgba(0,0,0,.2);
    }
    div.thumbnail div.img img
    {
        width:100%;
    }
    div.thumbnail div.img
    {
        text-align:center;
        padding-top:15px;
    }    
    div.thumbnail span.price
    {
        margin-top:2px;
        font-weight:bold;
    }
    div.thumbnail .pk-info
    {
        text-align:center;
    }
    div.thumbnail .pk-extra
    {
        color:#777;
        font-size:12px;
        font-weight:bold;
    }
    div.thumbnail .pk-extra .author-name
    {
        margin-bottom:3px;
    }
    div.thumbnail .download
    {
        color:#777;
        font-weight:normal;
    } 
    div.thumbnail {
        background-color: #fffeef;
    }
</style>
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
            <li id="demo"><a href="https://www.koolreport.com/examples">Online Examples</a></li>  
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
                            <a href="reports/basic/sales_by_customer/index.php" class="pk">
                                <div class="thumbnail">
                                    <div class="img">
                                        <img src="assets/images/basic1.png" alt="...">
                                    </div>
                                    <div class="caption">
                                        <div class="pk-info">
                                            <h3><strong>Sales By Customer</strong></h3>
                                            <p>Get the top ten customers who pay the most.</p>
                                        </div>
                                        
                                    </div>                        
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-4">
                            <a href="reports/basic/sakila_rental/index.php" class="pk">
                                <div class="thumbnail">
                                    <div class="img">
                                        <img src="assets/images/basic2.png" alt="...">
                                    </div>
                                    <div class="caption">
                                        <div class="pk-info">
                                            <h3><strong>Sakila Rental Report</strong></h3>
                                            <p>Get the sale report by month.</p>
                                        </div>
                                    </div>                        
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-4">
                            <a href="reports/basic/database_connection/index.php" class="pk">
                                <div class="thumbnail">
                                    <div class="img">
                                        <img src="assets/images/basic3.png" alt="...">
                                    </div>
                                    <div class="caption">
                                        <div class="pk-info">
                                            <h3><strong>Sakila Rental Report With Database</strong></h3>
                                            <p>Connect to Sakila database using PdoDataSource.</p>
                                        </div>
                                    </div>                        
                                </div>
                            </a>
                        </div> 
                        
                        <div class="col-md-4">
                            <a href="reports/basic/format_value/index.php" class="pk">
                                <div class="thumbnail">
                                    <div class="img">
                                        <img src="assets/images/basic6.png" alt="...">
                                    </div>
                                    <div class="caption">
                                        <div class="pk-info">
                                            <h3><strong>Format value in column</strong></h3>
                                            <p>Show how to format value in table's column.</p>
                                        </div>
                                    </div>                        
                                </div>
                            </a>
                        </div>                                                                 
                        <div class="col-md-4">
                            <a href="reports/basic/sales_by_country/index.php" class="pk">
                                <div class="thumbnail">
                                    <div class="img">
                                        <img src="assets/images/basic4.png" alt="...">
                                    </div>
                                    <div class="caption">
                                        <div class="pk-info">
                                            <h3><strong>Sale By Country with GeoChart</strong></h3>
                                            <p>Use Geochart in report.</p>
                                        </div>
                                    </div>                        
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-4">
                            <a href="reports/basic/table_demo/index.php" class="pk">
                                <div class="thumbnail">
                                    <div class="img">
                                        <img src="assets/images/basic5.png" alt="...">
                                    </div>
                                    <div class="caption">
                                        <div class="pk-info">
                                            <h3><strong>Table Demo</strong></h3>
                                            <p>This example demonstrates the capability and usage of table.</p>
                                        </div>
                                    </div>                        
                                </div>
                            </a>
                        </div>
                        
                    </div>            
                </div>
                <div class="bs-docs-section">
                    <h1 id="advanced">Advanced examples</h1>
                    <div class="row">
                    
                        <div class="col-md-4">
                            <a href="reports/advanced/input_and_export/index.php" class="pk">
                                <div class="thumbnail">
                                    <div class="img">
                                        <img src="assets/images/advance1.png" alt="...">
                                    </div>
                                    <div class="caption">
                                        <div class="pk-info">
                                            <h3><strong>Order List</strong></h3>
                                            <p>View order of a customer, export report.</p>
                                        </div>
                                    </div>                        
                                </div>
                            </a>
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
                            <a href="reports/export/sakila_rental/index.php" class="pk">
                                <div class="thumbnail">
                                    <div class="img">
                                        <img src="assets/images/export1.png" alt="...">
                                    </div>
                                    <div class="caption">
                                        <div class="pk-info">
                                            <h3><strong>Export Sakila Rental Report</strong></h3>
                                            <p>Export report to PDF.</p>
                                        </div>
                                    </div>                        
                                </div>
                            </a>
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
                            <a href="reports/cube/sales_by_quarters/index.php" class="pk">
                              <div class="thumbnail">
                                  <div class="img">
                                      <img src="assets/images/cube1.png" alt="...">
                                  </div>
                                  <div class="caption">
                                      <div class="pk-info">
                                          <h3><strong>Sales by Quarters</strong></h3>
                                          <p>Get sales by the quarters.</p>
                                      </div>
                                  </div>                        
                              </div>
                            </a>
                        </div>
                        
                        <div class="col-md-4">
                            <a href="reports/cube/sales_by_months_years/index.php" class="pk">
                              <div class="thumbnail">
                                  <div class="img">
                                      <img src="assets/images/cube2.png" alt="...">
                                  </div>
                                  <div class="caption">
                                      <div class="pk-info">
                                          <h3><strong>Sales by Months and Years</strong></h3>
                                          <p>Get sales by months and years.</p>
                                      </div>
                                  </div>                        
                              </div>
                            </a>
                        </div>
                        
                        <div class="col-md-4">
                            <a href="reports/cube/sales_by_customers_products/index.php" class="pk">
                              <div class="thumbnail">
                                  <div class="img">
                                      <img src="assets/images/cube3.png" alt="...">
                                  </div>
                                  <div class="caption">
                                      <div class="pk-info">
                                          <h3><strong>Sales by Customers And Products</strong></h3>
                                          <p>Get sales by customers and products.</p>
                                      </div>
                                  </div>                        
                              </div>
                            </a>
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
                             <a href="reports/pivot/customers_categories_products/index.php" class="pk">
                              <div class="thumbnail">
                                  <div class="img">
                                      <img src="assets/images/pivot1.png" alt="...">
                                  </div>
                                  <div class="caption">
                                      <div class="pk-info">
                                          <h3><strong>Customers - Categories - Products Pivot</strong></h3>
                                          <p>Get pivot table for customers, categories and products.</p>
                                      </div>
                                  </div>                        
                              </div>
                            </a>
                        </div>
                        
                        <div class="col-md-4">
                            <a href="reports/pivot/years_months/index.php" class="pk">
                              <div class="thumbnail">
                                  <div class="img">
                                      <img src="assets/images/pivot2.png" alt="...">
                                  </div>
                                  <div class="caption">
                                      <div class="pk-info">
                                          <h3><strong>Years - Months Pivot</strong></h3>
                                          <p>Get pivot table for years and months.</p>
                                      </div>
                                      
                                  </div>                        
                              </div>
                            </a>
                        </div>
                        
                        <div class="col-md-4">
                            <a href="reports/pivot/years_months_customers_categories/index.php" class="pk">
                              <div class="thumbnail">
                                  <div class="img">
                                      <img src="assets/images/pivot3.png" alt="...">
                                  </div>
                                  <div class="caption">
                                      <div class="pk-info">
                                          <h3><strong>Years - Months - Customers - Categories Pivot</strong></h3>
                                          <p>Get pivot table for years, months, customers and categories.</p>
                                      </div>
                                  </div>                        
                              </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="reports/pivot/pivotmatrix/index.php" class="pk">
                              <div class="thumbnail">
                                  <div class="img">
                                      <img src="assets/images/pivot4.png" alt="...">
                                  </div>
                                  <div class="caption">
                                      <div class="pk-info">
                                          <h3><strong>PivotMatrix</strong></h3>
                                          <p>Get pivot matrix for years, months, customers and categories.</p>
                                      </div>
                                  </div>                        
                              </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="reports/pivot/pivot_extract/index.php" class="pk">
                              <div class="thumbnail">
                                  <div class="img">
                                      <img src="assets/images/pivot5.png" alt="...">
                                  </div>
                                  <div class="caption">
                                      <div class="pk-info">
                                          <h3><strong>PivotExtract</strong></h3>
                                          <p>Extract data table from a pivot data.</p>
                                      </div>
                                  </div>                        
                              </div>
                            </a>
                        </div>
                        
                    </div>           
                </div>
                
                <div class="bs-docs-section">
                    <h1 id="inputs">Inputs package</h1>
                    <p><code>Inputs</code> package helps you to build dynamic reports with user's inputs
                        Get the <code>Inputs</code> package <a href="https://www.koolreport.com/packages/inputs">in here</a>.
                    </p>
                    <div class="row">
                    
                        <div class="col-md-4">  
                            <a href="reports/inputs/intro/index.php" class="pk">
                              <div class="thumbnail">
                                  <div class="img">
                                      <img src="assets/images/input1.png" alt="...">
                                  </div>
                                  <div class="caption">
                                      <div class="pk-info">
                                          <h3><strong>Introduce controls</strong></h3>
                                          <p>List of all current controls in inputs.</p>
                                      </div>
                                  </div>                        
                              </div>
                            </a>
                        </div>    
                        
                        <div class="col-md-4">  
                            <a href="reports/inputs/order_list/index.php" class="pk">
                              <div class="thumbnail">
                                  <div class="img">
                                      <img src="assets/images/input2.png" alt="...">
                                  </div>
                                  <div class="caption">
                                      <div class="pk-info">
                                          <h3><strong>List of orders</strong></h3>
                                          <p>Get list of orders within date ranges and of selected customers.</p>
                                      </div>
                                      
                                  </div>                        
                              </div>
                            </a>
                        </div>  
                        
                    </div>                            
                </div>
                
                <div class="bs-docs-section">
                    <h1 id="barcode">Barcode package</h1>
                    <p><code>Barcode</code> package helps you to generate multiple types of barcode and qrcode
                        Get the <code>Barcode</code> package <a href="https://www.koolreport.com/packages/barcode">in here</a>.
                    </p>
                    <div class="row">
                    
                        <div class="col-md-4">  
                            <a href="reports/barcode/products/index.php" class="pk">
                              <div class="thumbnail">
                                  <div class="img">
                                      <img src="assets/images/barcode1.png" alt="...">
                                  </div>
                                  <div class="caption">
                                      <div class="pk-info">
                                          <h3><strong>Products' barcode</strong></h3>
                                          <p>Catalogs of products' barcode and infomation.</p>
                                      </div>
                                  </div>                        
                              </div>
                            </a>
                        </div>    
                        
                    </div>                            
                </div>

                <div class="bs-docs-section">
                    <h1 id="drilldown">DrilDown package</h1>
                    <p><code>DrillDown</code> package helps you to construct drilldown report
                        Get the <code>DrillDown</code> package <a href="https://www.koolreport.com/packages/drilldown">in here</a>.
                    </p>
                    <div class="row">
                    
                        <div class="col-md-4">  
                            <a href="reports/drilldown/drilldown/index.php" class="pk">
                              <div class="thumbnail">
                                  <div class="img">
                                      <img src="assets/images/drilldown_drilldown.png" alt="...">
                                  </div>
                                  <div class="caption">
                                      <div class="pk-info">
                                          <h3><strong>Sale by Time</strong></h3>
                                          <p>Construct sale by time using DrillDown</p>
                                      </div>
                                  </div>                        
                              </div>
                            </a>
                        </div>    
                        <div class="col-md-4">  
                            <a href="reports/drilldown/customdrilldown/index.php" class="pk">
                              <div class="thumbnail">
                                  <div class="img">
                                      <img src="assets/images/drilldown_customdrilldown.png" alt="...">
                                  </div>
                                  <div class="caption">
                                      <div class="pk-info">
                                          <h3><strong>Sale By Location</strong></h3>
                                          <p>Construct sale by location using CustomDrillDown</p>
                                      </div>
                                  </div>                        
                              </div>
                            </a>
                        </div>    
                        <div class="col-md-4">  
                            <a href="reports/drilldown/multiview/index.php" class="pk">
                              <div class="thumbnail">
                                  <div class="img">
                                      <img src="assets/images/drilldown_multiview.png" alt="...">
                                  </div>
                                  <div class="caption">
                                      <div class="pk-info">
                                          <h3><strong>Payment By Month</strong></h3>
                                          <p>View payment by month using MultiView</p>
                                      </div>
                                  </div>                        
                              </div>
                            </a>
                        </div>    
                        
                    </div>                            
                </div>


                <div class="bs-docs-section">
                    <h1 id="chartjs">ChartJS package</h1>
                    <p><code>ChartJS</code> provide beatiful charts for your data visualization
                        Get the <code>ChartJS</code> package <a href="https://www.koolreport.com/packages/chartjs">in here</a>.
                    </p>
                    <div class="row">
                    
                        <div class="col-md-4">  
                            <a href="reports/chartjs/intro/index.php" class="pk">
                              <div class="thumbnail">
                                  <div class="img">
                                      <img src="assets/images/chartjs1.png" alt="...">
                                  </div>
                                  <div class="caption">
                                      <div class="pk-info">
                                          <h3><strong>Introduce charts</strong></h3>
                                          <p>List supported charts from ChartJS</p>
                                      </div>
                                  </div>                        
                              </div>
                            </a>
                        </div>    
                        
                    </div>                            
                </div>



                <div class="bs-docs-section">
                    <h1 id="more-examples">More examples</h1>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <ul>
                                <li><a href="reports/others/subreport_demo/index.php">Example of SubReport, Inputs and Ajax Report Loading</a></li>
                            </ul>
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
                    <h2 id="koolreport-pro">KoolReport Pro</h2>
                    <p>
                        <b>KoolReport Pro</b> is a special version including the latest KoolReport and all
                        commercial packages. It is back with Priority Support from us.
                        The KoolReport pro comes with <b>Developer License</b> and <b>Enterprise License</b>.
                    </p>
                    <p>
                        <a href="https://www.koolreport.com/get-koolreport-pro">Click here for more details</a>.
                    </p>
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
                        <li><a href="#inputs">Inputs package</a></li>
                        <li><a href="#barcode">Barcode package</a></li>
                        <li><a href="#drilldown">DrillDown package</a></li>
                        <li><a href="#chartjs">ChartJS package</a></li>
                        <li><a href="#more-examples">More examples</a></li>
                        <li><a href="#instruction">Instruction</a>
                            <ul class="nav">
                                <li><a href="#setup-databases">Setup databases</a></li>
                                <li><a href="#extended-packages">Extended packages</a></li>
                                <li><a href="#koolreport-pro">KoolReport Pro</a></li>
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
			    <li><a href="https://github.com/koolphp/koolreport" target="_blank">Github</a></li>
			    <li><a href="https://twitter.com/GetKoolReport" target="_blank">Twitter</a></li>
			    <li><a href="https://www.facebook.com/koolreport/" target="_blank">Facebook</a></li>
                <li><a href="https://www.koolreport.com/get-koolreport-pro">KoolReport Pro</a></li>
                <li><a href="https://www.koolreport.com/services">Services</a></li>
                <li><a href="https://www.koolreport.com/testimonials">Testimonials</a></li>
                <li><a href="https://www.koolreport.com/affiliate-program">Affiliate Program</a></li>
                <li><a href="https://www.koolreport.com/about">About us</a></li>
            </ul>
            <p>Designed and built with <i class="fa fa-heart-o text-danger"></i> by <a href="https://www.koolphp.net">KoolPHP Inc</a>.</p>
            <p>Code licensed <a href="https://www.koolreport/license">MIT</a>.</p>
        </div>
    </div>    
</body>
</html>