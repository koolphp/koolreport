<?php
require_once "InputAndExport.php";
$report = new InputAndExport;
$report->run()
->export('InputAndExport_pdf')
->pdf(array(
    "format"=>"A4",
    "orientation"=>"portrait"
))
->toBrowser("orders.pdf");