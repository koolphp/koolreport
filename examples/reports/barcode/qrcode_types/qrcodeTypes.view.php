<?php
use koolreport\barcode\QRCode;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>QRCode</title>
    <link rel="stylesheet" href="../../../assets/css/example.css" />
  </head>
  <body>
    <div class="container box-container">
    
      <div class='row kreport-title'>
        <h1>QRCode</h1>
      </div>
      <div class='row kreport-horizontal-line-0'>&nbsp;</div>
      
      <div class='row kreport-section-title'>
        <h3>QRCode</h3>
      </div>
      <div class='row kreport-horizontal-line-1'>&nbsp;</div>
      
      <div class='row'>
        <div class="col-md-4">SVG:</div>
        <div class="col-md-4">PNG:</div>
        <div class="col-md-4">JPG:</div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <?php 
            QRCode::create(array(
              "format" => "svg",
              "value"=>"http://koolreport.com",
            ));
          ?>
          <div>http://koolreport.com</div>
        </div>
        <div class="col-md-4">
          <?php 
            QRCode::create(array(
              "format" => "png",
              "value"=>"Test QRCode",
            ));
          ?>
          <div>http://koolreport.com</div>
        </div>
        <div class="col-md-4">
          <?php 
            QRCode::create(array(
              "format" => "jpg",
              "value"=>"Test QRCode",
            ));
          ?>
          <div>http://koolreport.com</div>
        </div>
      </div>
    </div>
  </body>
</html>