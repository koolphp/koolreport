<?php
/**
 * This file is wrapper class for Google WordTree 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\widgets\google;


class WordTree extends Chart
{
	protected function loadLibrary()
	{
		// $this->template("LoadLibrary",array(
			// "chartId"=>$this->chartId,
			// "zone"=>"current",
			// "packages"=>array("wordtree")
		// ));
    $this->getReport()->getResourceManager()
      ->addScriptOnEnd("google.charts.load('current', {'packages':['wordtree']})");
	}	
	static function create($params)
	{
		$component = new WordTree($params);
		$component->render();
	}
}
