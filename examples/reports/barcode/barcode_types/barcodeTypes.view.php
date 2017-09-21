<?php
use koolreport\barcode\BarCode;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Barcode Types</title>
    <link rel="stylesheet" href="../../../assets/css/example.css" />
  </head>
  <body>
    <div class="container box-container">
    
      <div class='row kreport-title'>
        <h1>Barcode Types</h1>
      </div>
      <div class='row kreport-horizontal-line-0'>&nbsp;</div>
      
      <div class='row kreport-section-title'>
        <h3>Type Code 128</h3>
      </div>
      <div class='row kreport-horizontal-line-1'>&nbsp;</div>
      <div class='row'>
        <h4 class="col-md-6">HTML:</h4>
        <h4 class="col-md-6">SVG:</h4>
      </div>
      <div class='row'>
        <div class="col-md-6">
          <?php 
            Barcode::create(array(
              "format" => "html",
              "value"=>"081231723897",
              "type"=>"TYPE_CODE_128"
            ));
          ?>
          <div>081231723897</div>
        </div>
        <div class="col-md-6">
          <?php 
            Barcode::create(array(
              "format" => "svg",
              "value"=>"081231723897",
              "type"=>"TYPE_CODE_128"
            ));
          ?>
          <div>081231723897</div>
        </div>
      </div>
      <div class='row'>
        <h4 class="col-md-6">JPG:</h4>
        <h4 class="col-md-6">PNG:</h4>
      </div>
      <div class='row'>
        <div class="col-md-6">
          <?php 
            Barcode::create(array(
              "format" => "jpg",
              "value"=>"081231723897",
              "type"=>"TYPE_CODE_128"
            ));
          ?>
          <div>081231723897</div>
        </div>
        <div class="col-md-6">
          <?php 
            Barcode::create(array(
              "format" => "png",
              "value"=>"081231723897",
              "type"=>"TYPE_CODE_128"
            ));
          ?>
          <div>081231723897</div>
        </div>
      </div>
      
      <div class='row kreport-section-title'>
        <h3>Type Code 39</h3>
      </div>
      <div class='row kreport-horizontal-line-1'>&nbsp;</div>
      <div class="row">
        <h4 class="col-md-6">HTML:</h4>
      </div>
      <div class='row'>
        <div class="col-md-12">
          <?php 
            Barcode::create(array(
              "format" => "html",
              "value"=>"081231723897",
              "type"=>"TYPE_CODE_39",
            ));
          ?>
          <div>081231723897</div>
        </div>
      </div>
      <div class="row">
        <h4 class="col-md-6">SVG:</h4>
      </div>
      <div class='row'>
        <div class="col-md-12">
          <?php 
            Barcode::create(array(
              "format" => "svg",
              "value"=>"081231723897",
              "type"=>"TYPE_CODE_39",
            ));
          ?>
          <div>081231723897</div>
        </div>
      </div>
      <div class="row">
        <h4 class="col-md-6">JPG:</h4>
      </div>
      <div class='row'>
        <div class="col-md-12">
          <?php 
            Barcode::create(array(
              "format" => "jpg",
              "value"=>"081231723897",
              "type"=>"TYPE_CODE_39",
            ));
          ?>
          <div>081231723897</div>
        </div>
      </div>
      <div class="row">
        <h4 class="col-md-6">PNG:</h4>
      </div>
      <div class='row'>
        <div class="col-md-12">
          <?php 
            Barcode::create(array(
              "format" => "png",
              "value"=>"081231723897",
              "type"=>"TYPE_CODE_39",
            ));
          ?>
          <div>081231723897</div>
        </div>
      </div>
      
    </div>
  </body>
</html>