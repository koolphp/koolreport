<?php

namespace koolreport\widgets\google;


class ScatterChart extends Chart
{
	static function create($params)
	{
		$component = new ScatterChart($params);
		$component->render();
	}
}