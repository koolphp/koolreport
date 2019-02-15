# KoolReport

### KoolReport is an intuitive and flexible Open-Source PHP Reporting Framework for faster and easier report delivery. It gives you full control of data process as well as data visualization. It is fast, simple and can be extended in many ways.

[![View examples](https://www.koolreport.com/assets/images/reporting_system.png)](https://www.koolreport.com/examples)


# Features

1. Various datasources including relational database MySQL, SQL Server, Oracle or NoSQL like MongoDB, CSV or Excel files.
2. Various data processes: data transformation, join, group, filter and etc.
3. Awesome visualization through widgets charts and tables.
4. Extended packages can be found at [KoolReport Extended Packages](https://www.koolreport.com/packages)

# Changelog 

* [Version 3.25.4 (Feb 15th, 2019)](https://www.koolreport.com/docs/koolreport/change_log/#version-3.25.4)
* [Version 3.25.3 (Feb 14th, 2019)](https://www.koolreport.com/docs/koolreport/change_log/#version-3.25.3)
* [Version 3.25.1 (Feb 13rd, 2019)](https://www.koolreport.com/docs/koolreport/change_log/#version-3.25.1)
* [Version 3.25.0 (Feb 11st, 2019)](https://www.koolreport.com/docs/koolreport/change_log/#version-3.25.0)
* [Version 3.1.0 (Nov 17th, 2018)](https://www.koolreport.com/docs/koolreport/change_log/#version-3.1.0)
* [Version 3.0.0 (Nov 13rd, 2018)](https://www.koolreport.com/docs/koolreport/change_log/#version-3.0.0)
* [Version 2.78.0 (Jul 16th, 2018)](https://www.koolreport.com/updates#version-2780)
* [Version 2.43.0 (Apr 20th, 2018)](https://www.koolreport.com/updates#version-2430)
* [Version 2.42.0 (Mar 19th, 2018)](https://www.koolreport.com/updates#version-2420)
* [Version 2.41.3 (Mar 02nd, 2018)](https://www.koolreport.com/updates#version-2413)
* [Version 2.31.8 (Feb 05th, 2018)](https://www.koolreport.com/updates#version-2318)
* [Version 2.31.7 (Jan 26th, 2018)](https://www.koolreport.com/updates#version-2317)
* [Version 2.0.0 (Dec 23rd, 2017)](https://www.koolreport.com/updates#version-200)
* [Version 1.72.8 (Oct 30th, 2017)](https://www.koolreport.com/updates#version-1728)
* [Version 1.61.5 (Sep 27th, 2017)](https://www.koolreport.com/updates#version-1615)
* [Version 1.61.2 (Sep 21st, 2017)](https://www.koolreport.com/updates#version-1612)
* [Version 1.47.3 (Aug 23rd, 2017)](https://www.koolreport.com/updates#version-1473)
* [Version 1.34.9 (Jul 25th, 2017)](https://www.koolreport.com/updates#version-1349)
* [Version 1.32.8 (Jul 5th, 2017)](https://www.koolreport.com/updates#version-1328)
* [Version 1.27.6 (Jun 7th, 2017)](https://www.koolreport.com/updates#version-1276)
* [Version 1.15.4 (May 22th, 2017)](https://www.koolreport.com/updates#version-1154)
* [Version 1.11.4 (May 4th, 2017)](https://www.koolreport.com/updates#version-1114)
* [Version 1.0.0 (April 3rd, 2017)](https://www.koolreport.com/updates#version-100)

# Get started

You can either clone our project or download directly from [KoolReport Download](https://www.koolreport.com/getting-started#download).

If you use `composer`, you can run following command to install the latest version

```
$ composer require koolphp/koolreport
```

## What's included

```
koolreport/
├── packages/
├── src/
│   ├── clients/
│   ├── core/
│   ├── datasources/
│   ├── processes/
│   └── widgets/
├── tests/
└── autoload.php
```

## System Requirement
1. PHP 5.4 or higher

# Installation
Copy the folder `koolreport` into your project and it is ready to run. You can start creating report.


# Create your first report

Make two files `SalesByCustomer.php` and `SalesByCustomer.view.php`

```
/
├── koolreport/
├── SalesByCustomer.php
├── SalesByCustomer.view.php
└── index.php
```

#### index.php

```php
<?php
// index.php: Just a bootstrap file
require_once "SalesByCustomer.php";

$salesByCustomer = new SalesByCustomer;
$salesByCustomer->run()->render();
```

#### SalesByCustomer.php

```php
<?php
require_once "../koolreport/autoload.php";

//Specify some data processes that will be used to process
use \koolreport\processes\Group;
use \koolreport\processes\Sort;
use \koolreport\processes\Limit;

//Define the class
class SalesByCustomer extends \koolreport\KoolReport
{    
    protected function settings()
    {
        //Define the "sales" data source which is the orders.csv 
        return array(
            "dataSources"=>array(
                "sales"=>array(
                    "class"=>'\koolreport\datasources\CSVDataSource',
                    "filePath"=>"orders.csv",
                ),        
            )
        );
    }
  
    protected function setup()
    {
        //Select the data source then pipe data through various process
        //until it reach the end which is the dataStore named "sales_by_customer".
        $this->src('sales')
        ->pipe(new Group(array(
            "by"=>"customerName",
            "sum"=>"dollar_sales"
        )))
        ->pipe(new Sort(array(
            "dollar_sales"=>"desc"
        )))
        ->pipe(new Limit(array(10)))
        ->pipe($this->dataStore('sales_by_customer'));
    }
}
```

#### SalesByCustomer.view.php

```php
<?php 
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\google\BarChart;
?>

<div class="report-content">
    <div class="text-center">
        <h1>Sales By Customer</h1>
        <p class="lead">This report shows top 10 sales by customer</p>
    </div>

    <?php
    BarChart::create(array(
        "dataStore"=>$this->dataStore('sales_by_customer'),
        "width"=>"100%",
        "height"=>"500px",
        "columns"=>array(
            "customerName"=>array(
                "label"=>"Customer"
            ),
            "dollar_sales"=>array(
                "type"=>"number",
                "label"=>"Amount",
                "prefix"=>"$",
                "emphasis"=>true
            )
        ),
        "options"=>array(
            "title"=>"Sales By Customer",
        )
    ));
    ?>
    <?php
    Table::create(array(
        "dataStore"=>$this->dataStore('sales_by_customer'),
            "columns"=>array(
                "customerName"=>array(
                    "label"=>"Customer"
                ),
                "dollar_sales"=>array(
                    "type"=>"number",
                    "label"=>"Amount",
                    "prefix"=>"$",
                )
            ),
        "cssClass"=>array(
            "table"=>"table table-hover table-bordered"
        )
    ));
    ?>
</div>
```

You may download the source code [sale_report.zip](https://www.koolreport.com/docs/articles/quick_start/sale_report.zip). And the result of this report can be [previewed in here](https://www.koolreport.com/examples/reports/basic/sales_by_customer/).

# More examples

1. [Products vs Quarters](https://www.koolreport.com/examples/reports/cube/cube_products_vs_quarters/)
2. [Export To PDF](https://www.koolreport.com/examples/reports/export/sakila_rental/)
3. [DrillDown Report](https://www.koolreport.com/examples/reports/drilldown/drilldown/)
4. [Pivot Table](https://www.koolreport.com/examples/reports/pivot/customers_categories_products/)

.. and [all examples](https://www.koolreport.com/examples/).

# Licenses
The KoolReport is licensed under MIT License.

# Extended packages:

* [Laravel](https://www.koolreport.com/packages/laravel): Make KoolReport work seamlessly in Laravel PHP Framework [__Free__]
* [CodeIgniter](https://www.koolreport.com/packages/codeigniter): Make KoolReport work seamlessly in CodeIgniter Framework [__Free__]
* [MongoDB](https://www.koolreport.com/packages/mongodb): Build report from MongoDB [__Free__]
* [CleanData](https://www.koolreport.com/packages/cleandata): Solution for your missing data [__Free__]
* [QueryBuilder](https://www.koolreport.com/packages/querybuilder): Build fast and error-free SQL Query for your report [__Free__]
* [Instant](https://www.koolreport.com/packages/instant): Create widget instantly without setting up a full report [__Free__]
* [Cache](https://www.koolreport.com/packages/cache): Drive the speed and responsiveness of your report to the max [__Free__]
* [Statistics](https://www.koolreport.com/packages/statistics): Provide various statistical measures for your data [__Free__]
* [Bootstrap3](https://www.koolreport.com/packages/bootstrap3): Create beautiful report with Bootstrap 3 [__Free__]
* [Bootstrap4](https://www.koolreport.com/packages/bootstrap3): Create modern report with Bootstrap 4 [__Free__]
* [CloudExport](https://www.koolreport.com/packages/cloudexport): Export your report to PDF, JPG, PNG and other formats using cloud services [__Free__]
* [Excel](https://www.koolreport.com/packages/excel): Import and export data to Excel [__$29__]
* [Cube](https://www.koolreport.com/packages/cube): Turn your data into two dimensions cross-tab table [__$19__]
* [Pivot](https://www.koolreport.com/packages/pivot): Build multi-dimenstional pivot table [__$49__]
* [Export](https://www.koolreport.com/packages/export): Export your report to PDF, JPG, PNG and other formats [__$29__]
* [Morris Chart](https://www.koolreport.com/packages/morris_chart): Use morris charts in your report [__$9__]
* [Inputs](https://www.koolreport.com/packages/inputs): A simplest way to build dynamic reports [__$29__]
* [BarCode](https://www.koolreport.com/packages/barcode): Generate BarCode and QRCode [__$9__]
* [Sparklines](https://www.koolreport.com/packages/sparklines): Create tiny charts [__$9__]
* [DataGrid](https://www.koolreport.com/packages/datagrid): Display data in table format, search, sort, group data and more.. [__$19__]
* [DrillDown](https://www.koolreport.com/packages/drilldown): Build awesome drill-down, drill-through report easily [__$29__]
* [ChartJS](https://www.koolreport.com/packages/chartjs): One of the most beautiful and powerful chart libraries for your report [__$29__]


# KoolReport Pro

[KoolReport Pro](https://www.koolreport.com/get-koolreport-pro) is the most advanced version of KoolReport. It includes latest KoolReport version plus all available advanced packages from KoolPHP Inc. It costs __$129__ for Developer License and __$349__ for Enterprise License. The licenses come with following benefits:

1. Include all __current package and future ones__.
2. Deploying on __unlimited servers or projects__.
3. __Royal free__ (no extra charged) when you delivered the KoolReport Pro with your commercial product.
4. __1 year subscription__ to get free upgrades, bugs fixed and new released packages regardless price.
5. __1 year priority support__ on forum. Your post will be marked with star and got fatest response by our expert.
6. Enterprise License allows __unlimited number of developers__ in organization.
7. __50% discount__ on the next subscription.

If you have further inquiry on this special version, please go to our [forum](https://www.koolreport.com/forum/topics) or email us at [support@koolreport.com](mailto:support@koolreport.com). We will get back to you in no time.

# Some thoughts

KoolReport is the sum of our many years experience in making reports for our customers. We hope that it will help you to create powerful report with less effort. We love KoolReport very much and want it to be alive. We hope that you do too.

Being alive means that the source code continues to evolve, more features are added, and everything is back with professional support.

Please consider your purchase of extended packages or subscription as your donation. In exchange, we would like to send you gifts which are extensions or support services. By this way, we together contribute to the existence of KoolReport.


# Our vision

__"If you want to go fast, go alone. If you want to grow far, go together"__ - *African Proverb*

We have learned above statement through years of software development. We choose open-source as software model for KoolReport because we understand that:

1. Alone, we never keep up with all time-changing needs
2. Alone, we never have enough man power to serve well.

That's why we designed KoolReport to be simple and extensible. Simple to use and extensible for change. We would like to grow it to be a solid framework supported by market of packages providing various datasources, data processes, visualization widgets and report templates where users can find solution right away.

KoolReport wants to be foundation or ecosystem connecting people who need reporting solution and those who can provide it. All are invited to join us on market to provide excellent solutions. We believe people willing to pay for a good solution. And we are happy to share market and profit.

Vision are big, we take the first step. Join us!

# To-do-list

Below are the things we are going to do:

1. Evolve the structure of KoolReport and its functionalities
3. Build market to push
    1. Connection to various datasources
    2. Data processing focus on: data cleaning, data analytics, data mining techniques
    3. More widgets to visualize data
4. Build GUI for non-technical users
5. Build ecosystem to handle big-data

# Author
1. [KoolPHP Inc](https://www.koolphp.net) - A vendor of PHP components