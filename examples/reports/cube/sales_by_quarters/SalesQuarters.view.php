<?php
    use \koolreport\widgets\google;
    use \koolreport\widgets\koolphp\Table;
?>
<div class='box-container'>

  <div class='no-page-break'>
    <div class='row kreport-title'>
      <h1>Sales By Quarters</h1>
    </div>
    <div class='row kreport-horizontal-line-0'>&nbsp;</div>
    <div class='row kreport-section-title'>
      <h3>Top 5 Customers</h3>
    </div>
    <div class='row kreport-horizontal-line-1'>&nbsp;</div>
    <div class='row'>
      <div class="col-xs-12">
        <?php 
          Table::create(array(
            "dataStore" => $this->dataStore('salesQuarterCustomer'),
          ));
        ?>
      </div>
    </div>
    <div class='row'>
      <div class="col-xs-6">
        <?php 
          google\BarChart::create(array(
            "dataStore"=>$this->dataStore('salesQuarterCustomerNoAll'),
            "options"=>array(
              'title' => 'Top 5 Customers\' Quarterly Sales',
              'isStacked' => true
            ),
            "width"=>'100%',
            // 'height'=>'400px',
          ));
        ?>
      </div>
      <div class="col-xs-6">
        <?php 
          google\PieChart::create(array(
            "dataStore"=>$this->dataStore('salesQuarterCustomerAll'),
            "options"=>array(
              'title' => 'Top 5 Customers\' Yearly Sales',
              // 'legend' => 'bottom',
              // 'is3D' => true,
            ),
            "width"=>'100%',
            // 'height'=>'300px',
          ));
        ?>
      </div>
    </div>
    <div class='row kreport-horizontal-line-2'>&nbsp;</div>
  </div>
  
  <div class='page'>
    <div class='row kreport-section-title'>
      <h3>Top 5 Products</h3>
    </div>
    <div class='row kreport-horizontal-line-1'>&nbsp;</div>
    <div class='row'>
      <div class="col-xs-12">
        <?php 
          Table::create(array(
            "dataStore" => $this->dataStore('salesQuarterProductName'),
          ));
        ?>
      </div>
    </div>
    <div class='row'>
      <div class="col-xs-6">
        <?php 
          google\BarChart::create(array(
            "dataStore"=>$this->dataStore('salesQuarterProductNameNoAll'),
            "options"=>array(
              'title' => 'Top 5 Products\' Quarterly Sales',
              'isStacked' => true
            ),
            "width"=>'100%',
            // 'height'=>'400px',
          ));
        ?>
      </div>
      <div class="col-xs-6">
        <?php 
          google\PieChart::create(array(
            "dataStore"=>$this->dataStore('salesQuarterProductNameAll'),
            "options"=>array(
              'title' => 'Top 5 Products\' Yearly Sales',
            ),
            "width"=>'100%',
            // 'height'=>'300px',
          ));
        ?>
      </div>
    </div>
    <div class='row kreport-horizontal-line-2'>&nbsp;</div>
  </div>
  
  <div class='page'>
    <div class='row kreport-section-title'>
      <h3>Top 5 Categories</h3>
    </div>
    <div class='row kreport-horizontal-line-1'>&nbsp;</div>
    <div class='row'>
      <div class="col-xs-12">
        <?php 
          Table::create(array(
            "dataStore" => $this->dataStore('salesQuarterProductLine'),
          ));
        ?>
      </div>
    </div>
    <div class='row'>
      <div class="col-xs-6">
        <?php 
          google\BarChart::create(array(
            "dataStore"=>$this->dataStore('salesQuarterProductLineNoAll'),
            "options"=>array(
              'title' => 'Top 5 Categories\' Quarterly Sales',
              'isStacked' => true
            ),
            "width"=>'100%',
            // 'height'=>'400px',
          ));
        ?>
      </div>
      <div class="col-xs-6">
        <?php 
          google\PieChart::create(array(
            "dataStore"=>$this->dataStore('salesQuarterProductLineAll'),
            "options"=>array(
              'title' => 'Top 5 Categories\' Yearly Sales',
            ),
            "width"=>'100%',
            // 'height'=>'300px',
          ));
        ?>
      </div>
    </div>
    <div class='row kreport-horizontal-line-2'>&nbsp;</div>
  </div>

</div>
