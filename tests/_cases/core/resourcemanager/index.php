<?php
require_once "../../../../../../../koolreport/autoload.php";

require_once "Report.php";

$report = new Report;
$report->run()->render();
