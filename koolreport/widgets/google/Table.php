<?php

namespace koolreport\widgets\google;


class Table extends Chart
{
	protected function loadLibrary()
	{
		$this->template("LoadLibrary",array(
			"zone"=>"current",
			"packages"=>array("table")
		));
	}	
	static function create($params)
	{
		$component = new Table($params);
		$component->render();
	}
}
