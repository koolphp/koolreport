<?php

namespace koolreport\widgets\google;


class PieChart extends Chart
{
	static function create($params)
	{
		$component = new PieChart($params);
		$component->render();
	}
}
