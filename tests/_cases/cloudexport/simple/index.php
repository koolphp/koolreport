<?php
require_once "../../../../autoload.php";
require_once "Report.php";

$report = new Report;
$report->run()->cloudExport()
->chromeHeadlessio("token-key")
->pdf()
->toBrowser("test.pdf");