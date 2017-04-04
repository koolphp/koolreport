<?php

namespace koolreport\widgets\google;


class OrgChart extends Chart
{
	protected function loadLibrary()
	{
		$this->template("LoadLibrary",array(
			"zone"=>"upcoming",
			"packages"=>array("map")
		));
	}	
	static function create($params)
	{
		$component = new OrgChart($params);
		$component->render();
	}
}
