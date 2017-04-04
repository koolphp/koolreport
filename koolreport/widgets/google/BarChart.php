<?php

namespace koolreport\widgets\google;


class BarChart extends Chart
{
	static function create($params)
	{
		$component = new BarChart($params);
		$component->render();
	}
}
