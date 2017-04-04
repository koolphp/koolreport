<?php

namespace koolreport\widgets\google;


class AreaChart extends Chart
{
	static function create($params)
	{
		$component = new AreaChart($params);
		$component->render();
	}
}