<?php

namespace koolreport\widgets\google;


class Gauge extends Chart
{
	static function create($params)
	{
		$component = new Gauge($params);
		$component->render();
	}
}
