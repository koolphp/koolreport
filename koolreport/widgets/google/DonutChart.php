<?php
/**
 * This file is wrapper class for Google DonutChart 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\widgets\google;


class DonutChart extends Chart
{
	protected function OnInit()
	{
		parent::onInit();
		$this->type ="PieChart";
		if(!isset($this->options["pieHole"]))
		{
			$this->options["pieHole"] = 0.4;
		}
	}
	
	static function create($params)
	{
		$component = new DonutChart($params);
		$component->render();
	}
}
