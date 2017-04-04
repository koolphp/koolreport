<?php

namespace koolreport\widgets\google;


class Map extends Chart
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
		$component = new Map($params);
		$component->render();
	}
}
