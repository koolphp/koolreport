<?php

namespace koolreport\widgets\google;


class LineChart extends Chart
{
	static function create($params)
	{
		$component = new LineChart($params);
		$component->render();
	}
}
