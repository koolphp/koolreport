<?php
/**
 * This file is wrapper class for Google GeoChart 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\widgets\google;


class GeoChart extends Chart
{
	protected function loadLibrary()
	{
		// $this->template("LoadLibrary",array(
			// "chartId"=>$this->chartId,
			// "zone"=>"current",
			// "packages"=>array("geochart")
		// ));
    $this->getReport()->getResourceManager()
      ->addScriptOnEnd("google.charts.load('current', {'packages':['geochart']})");
	}	
	static function create($params)
	{
		$component = new GeoChart($params);
		$component->render();
	}
}
