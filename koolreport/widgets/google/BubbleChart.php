<?php

namespace koolreport\widgets\google;


class BubbleChart extends Chart
{
	static function create($params)
	{
		$component = new BubbleChart($params);
		$component->render();
	}
}