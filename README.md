# KoolReport

KoolReport is an intuitive and flexible open-source PHP reporting framework for faster and easier report delivery. It gives you full control of data process as well as data visualization. It is fast, simple and can be extended in many ways.

# Features

1. Various datasources including relational database MySQL, SQL Server, Oracle or nosql like MongoDB, CSV or Excel files.
2. Various data processes: data transformation, join, group, filter and etc.
3. Awesome visualization through widgets charts and tables.
4. Extended packages can be found at [KoolReport Extended Packages](https://www.koolreport.com/packages)

# Get started

You can either clone our project or download directly from [KoolReport Download](https://www.koolreport.com/getting-started#download).

## What's included

```
koolreport/
├── assets/
├── core/
│   ├── AssetManager.php
│   ├── Base.php
│   ├── DataSource.php
│   ├── DataStore.php
│   ├── Node.php
│   ├── Process.php
│   ├── ProcessGroup.php
│   ├── Utility.php
│   └── Widget.php
├── datasources/
│   ├── ArrayDataSource.php
│   ├── CSVDataSource.php
│   ├── ExcelDataSource.php
│   ├── MongoDataSource.php
│   └── PdoDataSource.php
├── packages/
├── processes/
│   ├── CalculatedColumn.php
│   ├── Custom.php
│   ├── DateTimeFormat.php
│   ├── Filter.php
│   ├── Group.php
│   ├── Join.php
│   ├── Limit.php
│   ├── NumberBucket.php
│   ├── NumberRange.php
│   ├── TimeBucket.php
│   └── ... many other processes
├── vendor/
│   ├── composer/
│   ├── mongodb/
│   ├── phpoffice/
│   └── autoload.php
├── widgets/
│   ├── google/
│   │   ├── LineChart.php
│   │   ├── BubbleChart.php
│   │   ├── BarChart.php
│   │   ├── ColumnChart.php
│   │   ├── DonutChart.php
│   │   └── .. and many other charts from google :)
│   └── koolphp
│      ├── Table.php 
│      └── Table.tpl.php
├── autoload.php
├── composer.json
└── KoolReport.php
```
## System Requirement
1. PHP 5.3.x or higher

# Installation
Copy the folder `koolreport` into your project and it is ready to run. You can start creating report.


# Create your first report

Make two file `SalesByCustomer.php` and `SalesByCustomer.view.php`

```
/
├── koolreport/
├── SalesByCustomer.php
├── SalesByCustomer.view.php
└── index.php
```

```php
<?php
// index.php: Just a bootstrap file
require_once "SalesByCustomer.php";

$salesByCustomer = new SalesByCustomer;
$salesByCustomer->run()->render();
```

```php
<?php
// SalesByCustomer.php - Report setup file
require_once "koolreport/autoload.php";
use \koolreport\processes\Group;

class SalesByCustomer extends \koolreport\KoolReport
{
    public function settings()
    {
        return array(
            "datasources"=>array(
                "sales"=>array(
                    "connectionString"=>"mysql:host=localhost;dbname=db_sales",
                    "username"=>"root",
                    "password"=>"",
                    "charset"=>"utf8"
                )
            )
        );
    }

    public function setup()
    {
        $this->src('sales')
        ->query("SELECT customerName,dollar_sales FROM customer_product_dollarsales")
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

```php
<?php 
// SalesByCustomer.view.php - Handle the report view
use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\google\BarChart;
?>

<div class="text-center">
    <h1>Sales Report</h1>
    <h4>This report shows top 10 sales by customer</h4>
</div>
<hr/>

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
            )
        ),
        "options"=>array(
            "title"=>"Sales By Customer"
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
```

Result of this report can be [preview in here](https://www.koolreport.com/examples/reports/basic/sales_by_customer/index.php).

# More examples

1. [Sale By Quarter](https://www.koolreport.com/examples/reports/cube/sales_by_quarters/index.php)
2. [Sales By Months and Years](https://www.koolreport.com/examples/reports/cube/sales_by_months_years/index.php)
3. [Sales By Customers And Products](https://www.koolreport.com/examples/reports/cube/sales_by_customers_products/index.php)

# Licenses
The KoolReport is licensed under MIT License.

# Our vision

__"If you want to go fast, go alone. If you want to grow far, go together"__ - *African Proverb*

We have learned above statement through years of software development. We choose open-source as software model for KoolReport because we understand that:

1. Alone, we never keep up with all time-changing needs
2. Alone, we never have enough man power to serve well.

That's why we designed KoolReport to be simple and extensible. Simple to use and exntensible for change. We would like to grow it to be a solid framework supported by market of packages providing various datasources, data processes, visualization widgets and report templates where users can find solution right away.

KoolReport wants to be foundation or ecosystem connecting people who need reporting solution and those who can provide it. All are invited to join us on market to provide excellent solutions. We believe people willing to pay for a good solution. And we are happy to share market and profit.

Vision are big, we take the first step. Join us!

# To-do-list

Below are the things we are going to do:

1. Evolve the structure of KoolReport and its functionalities
2. Export to report to PDF and other formats
3. Build market to push
 ..* Connection to various datasources
 ..* Data processing focus on: data cleaning, data analytics, data mining tecnique
 ..* More widgets to visualize data
4. Build GUI for non technical users
5. Build ecosystem to handle big-data

# Author
1. [KoolPHP Inc](https://www.koolphp.net) - A vendor of PHP components



