<?php

namespace koolreport\widgets\google;


class CandlestickChart extends Chart
{
	static function create($params)
	{
		$component = new CandlestickChart($params);
		$component->render();
	}
}