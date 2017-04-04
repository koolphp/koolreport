<?php

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
