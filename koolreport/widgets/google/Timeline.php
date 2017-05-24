<?php
/**
 * This file is wrapper class for Google Timeline 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\widgets\google;


class Timeline extends Chart
{
	protected function loadLibrary()
	{
		// $this->template("LoadLibrary",array(
			// "chartId"=>$this->chartId,
			// "zone"=>"current",
			// "packages"=>array("timeline")
		// ));
    $this->getReport()->getResourceManager()
      ->addScriptOnEnd("google.charts.load('current', {'packages':['timeline']})");
	}	
	static function create($params)
	{
		$component = new Timeline($params);
		$component->render();
	}
}
