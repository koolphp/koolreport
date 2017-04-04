<?php

namespace koolreport\widgets\google;


class Sankey extends Chart
{
	protected function loadLibrary()
	{
		$this->template("LoadLibrary",array(
			"zone"=>"current",
			"packages"=>array("sankey")
		));
	}	
	static function create($params)
	{
		$component = new Sankey($params);
		$component->render();
	}
}
