<?php
require_once "SalesPivotExtract.php";
$report = new SalesPivotExtract();

$report->run()
->export("SalesPivotExtractPdf")
->pdf(
  array(
    // "format"=>"A4",
    // "orientation"=>"landscape",
    "width" => '29cm',
    "height" => '21cm',
    "margin" => '0cm'
  )
)
->toBrowser("SalesPivotExtract.pdf");