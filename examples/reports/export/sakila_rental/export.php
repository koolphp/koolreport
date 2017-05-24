<?php
require_once "SakilaRental.php";
$report = new SakilaRental;

$report->run()
->export('SakilaRentalPdf')
->pdf(array(
    "format"=>"A4",
    "orientation"=>"portrait"
))
->toBrowser("sakila_rental.pdf");