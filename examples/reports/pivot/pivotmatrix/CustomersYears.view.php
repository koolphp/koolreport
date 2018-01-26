<!DOCTYPE html>
<html>
<head>
    <title>Pivot Table of Customers and Categories - Products</title>
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/css/example.css" />
</head>
<style>
    .box-container {
    width: 29cm !important;
    }
    .pivot-data-cell {
    text-align: right;
    }
    h1 {
    text-align: center;
    }
</style>
<body>
    <div class="container box-container">
        
        <h1>Sales By Years - Months - Customers - Categories</h1>
        <?php
            require_once "../../../../koolreport/autoload.php";
            use \koolreport\pivot\widgets\PivotMatrix;
            use \koolreport\core\Utility;
            use \koolreport\processes\Filter;
            use \koolreport\processes\ColumnMeta;
            use \koolreport\pivot\processes\Pivot;
            use \koolreport\instant\Widget;
            use \koolreport\datasources\CSVDataSource;
        ?>
        
        <div>
            <form id='form1' class="form-inline" method="post">

                <?php
                PivotMatrix::create(array(
                    "id" => "pivotMatrix1",
                    'dataSource' => $this->dataStore('sales'),
                    "measures"=>array(
                        "dollar_sales - sum", 
                    ),
                    'rowSort' => array(
                        'dollar_sales - sum' => 'desc',
                        'productLine' => 'desc',
                    ),
                    'columnSort' => array(
                        'orderMonth' => function($a, $b) {
                            return (int)$a < (int)$b;
                        },
                    ),
                    'columnCollapseLevels' => array(0),
                    'rowCollapseLevels' => array(0),
                    'width' => '100%',
                    'height' => '500px',
                    'headerMap' => function($v, $f) {
                        switch ($v) {
                            case 'dollar_sales - sum': return 'Total Sales';
                            case 'dollar_sales - count': return 'Number of Sales';
                            case 'dollar_sales - avg': return 'Average Sales';
                            case 'orderYear': return 'Year';
                            case 'orderMonth': return 'Month';
                            case 'orderDay': return 'Day';
                            case 'customerName': return 'Customer';
                            case 'productLine': return 'Category';
                            case 'productName': return 'Product';
                        }
                        $r = $v;
                        if ($f === 'orderYear')
                            $r = 'Year ' . $v;
                        $map = array(
                            '1' => 'January',
                            '2' => 'February',
                            '3' => 'March',
                            '4' => 'April',
                            '5' => 'May',
                            '6' => 'June',
                            '7' => 'July',
                            '8' => 'August',
                            '9' => 'September',
                            '10' => 'October',
                            '11' => 'November',
                            '12' => 'December',
                        );
                        if ($f === 'orderMonth')
                            $r = $map[$v];
                        return $r;
                    },
                    'totalName' => 'All',
                    'waitingFields' => array(
                        'dollar_sales - count' => 'data', 
                        'orderMonth' => 'label',
                        'orderDay' => 'label',
                    ),
                    'paging' => array(
                        'size' => 5,
                        'maxDisplayedPages' => 5,
                        'sizeSelect' => array(5, 10, 20, 50, 100)
                    )
                ));
                ?>
            </form>

        </div>
        
    </div>
</body>
</html>
