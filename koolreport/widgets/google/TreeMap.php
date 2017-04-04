<?php

namespace koolreport\widgets\google;


class TreeMap extends Chart
{
	protected function loadLibrary()
	{
		$this->template("LoadLibrary",array(
			"zone"=>"current",
			"packages"=>array("treemap")
		));
	}	
	static function create($params)
	{
		$component = new TreeMap($params);
		$component->render();
	}
}
