<?php

namespace koolreport\widgets\google;


class GeoChart extends Chart
{
	protected function loadLibrary()
	{
		$this->template("LoadLibrary",array(
			"zone"=>"upcoming",
			"packages"=>array("geochart")
		));
	}	
	static function create($params)
	{
		$component = new GeoChart($params);
		$component->render();
	}
}
