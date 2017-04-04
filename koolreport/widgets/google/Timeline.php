<?php

namespace koolreport\widgets\google;


class Timeline extends Chart
{
	protected function loadLibrary()
	{
		$this->template("LoadLibrary",array(
			"zone"=>"current",
			"packages"=>array("timeline")
		));
	}	
	static function create($params)
	{
		$component = new Timeline($params);
		$component->render();
	}
}
