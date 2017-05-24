<?php
/**
 * This file is wrapper class for Google Table 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\widgets\google;


class Table extends Chart
{
	protected function loadLibrary()
	{
		$this->template("LoadLibrary",array(
			"chartId"=>$this->chartId,
			"zone"=>"current",
			"packages"=>array("table")
		));
    $this->getReport()->getResourceManager()
      ->addScriptOnEnd("google.charts.load('current', {'packages':['table']})");
	}	
	static function create($params)
	{
		$component = new Table($params);
		$component->render();
	}
}
