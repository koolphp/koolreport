<?php
require_once "SalesQuarters.php";
$salesYear = isset($_POST['salesYear']) ? $_POST['salesYear'] :
    array(2003, 2004, 2005);
$report = new SalesQuarters(array(
  'salesYear' => $salesYear
));


$report->run()
// $ds = $report->dataStore('salesQuarterCustomer');
// print_r($ds->meta());
->exportToExcel(array(
  'dataStores' => array('salesQuarterCustomer')
))
->toBrowser("SalesQuarters.xlsx");