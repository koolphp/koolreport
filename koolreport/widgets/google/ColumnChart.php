<?php

namespace koolreport\widgets\google;


class ColumnChart extends Chart
{
	static function create($params)
	{
		$component = new ColumnChart($params);
		$component->render();
	}
}
