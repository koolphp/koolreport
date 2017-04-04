<?php

namespace koolreport\widgets\google;


class WordTree extends Chart
{
	protected function loadLibrary()
	{
		$this->template("LoadLibrary",array(
			"zone"=>"current",
			"packages"=>array("wordtree")
		));
	}	
	static function create($params)
	{
		$component = new WordTree($params);
		$component->render();
	}
}
