<?php
/**
 * This file is wrapper class for Google DonutChart 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
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
	

}
