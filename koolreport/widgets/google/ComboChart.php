<?php

namespace koolreport\widgets\google;


class ComboChart extends Chart
{
	static function create($params)
	{
		$component = new ComboChart($params);
		$component->render();
	}
}