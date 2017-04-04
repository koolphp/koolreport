<?php

namespace koolreport\widgets\google;


class SteppedAreaChart extends Chart
{
	static function create($params)
	{
		$component = new SteppedAreaChart($params);
		$component->render();
	}
}