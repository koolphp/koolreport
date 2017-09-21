<?php
/**
 * This file is wrapper class for Google Map 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\widgets\google;


class Map extends Chart
{
	protected function loadLibrary()
	{
		$this->getReport()->getResourceManager()
		->addScriptOnBegin("google.charts.load('current', {'packages':['map']})");
	}	

}
