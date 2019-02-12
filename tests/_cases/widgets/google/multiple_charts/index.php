<?php
require_once "../../../../../autoload.php";
require_once "Report.php";

$report = new Report;
$report->run()->render();
