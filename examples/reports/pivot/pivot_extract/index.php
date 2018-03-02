<?php
require_once "SalesPivotExtract.php";
$salesPivotExtract = new SalesPivotExtract;
$salesPivotExtract->run()->render();
?>    
