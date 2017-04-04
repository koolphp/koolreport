<?php

namespace koolreport\widgets\google;


class Histogram extends Chart
{
	static function create($params)
	{
		$component = new Histogram($params);
		$component->render();
	}
}
