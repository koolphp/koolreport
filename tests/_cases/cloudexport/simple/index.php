<?php
require_once "../../../../autoload.php";
require_once "Report.php";

$report = new Report;
$report->run()->cloudExport()
->chromeHeadlessio("753edd18c907f25905778a7de9a02b564475629cbf7b04928c5edc445befec93")
->pdf()
->toBrowser("test.pdf");