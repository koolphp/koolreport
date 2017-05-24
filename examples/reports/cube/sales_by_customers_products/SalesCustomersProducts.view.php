<?php
use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\google;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Sales By Customers and Categories</title>
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../../assets/css/example.css" />
  </head>
  <style>
    .box-container {
      width: 29cm;
    }
  </style>
  <body>
    <div class="container box-container">
    
      <div class='row kreport-title'>
        <h1>Sales By Customers and Categories</h1>
      </div>
      <div class='row kreport-horizontal-line-0'>&nbsp;</div>
      <div class='row kreport-section-title'>
        <h3>Top 5 Customers Detailed by Categories</h3>
      </div>
      <div class='row kreport-horizontal-line-1'>&nbsp;</div>
      <div class='row'>
        <div class="col-md-8">
          <?php 
            Table::create(array(
              "dataStore" => $this->dataStore('salesCustomerProductLine'),
              'cssClass' => array('table' => 'table-condensed')
            ));
          ?>
        </div>
        <div class="col-md-4">
          <?php 
            google\BarChart::create(array(
              "dataStore"=>$this->dataStore('salesCustomerProductLineAll'),
              "options"=>array(
                'title' => 'Top 5 Customers\' Total Sales',
                'legend' => 'bottom',
                'isStacked' => true
              ),
              'width' => '100%%',
            ));
          ?>
        </div>
      </div>
      <div class='row'>
          <?php 
            google\ColumnChart::create(array(
              "dataStore"=>$this->dataStore('salesCustomerProductLineNoAll'),
              "options"=>array(
                'title' => 'Top 5 Customers\' Sales by Categories',
              ),
              "width"=>'100%',
              // 'height'=>'300px',
            ));
          ?>
      </div>
      <div class='row kreport-horizontal-line-2'>&nbsp;</div>
      
    </div>
  </body>
</html>