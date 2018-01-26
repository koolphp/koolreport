<?php
/**
 * This file contains class to turn number data into group
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * ->pipe(new NumberBucket(array(
 * 		"sale"=>array(
 * 			"step"=>5,
 * 			"formatString"=>"{from} - {to}"
 * 			"decimals"=>0,
 * 			"thousandSeparator"=>",",
 * 			"decimalPoint"=>".",
 * 			"prefix"=>"",
 * 			"suffix"=>"",
 * 		)
 * )))
 * 
 */
namespace koolreport\processes;
use \koolreport\core\Process;
use \koolreport\core\Utility;

class NumberBucket extends Process
{
	protected function onMetaReceived($metaData)
	{
		foreach($this->params as $cName=>$cParam)
		{
			$metaData["columns"][$cName] = array(
				"type"=>"string",
			);
			$this->params[$cName]["formatString"] = Utility::get($cParam,"formatString","{from} - {to}");

			$this->params[$cName]["decimals"] = Utility::get($cParam,"decimals",0);
			$this->params[$cName]["thousandSeparator"] = Utility::get($cParam,"thousandSeparator",",");
			$this->params[$cName]["decimalPoint"] = Utility::get($cParam,"decimalPoint",".");
			$this->params[$cName]["prefix"] = Utility::get($cParam,"prefix","");
			$this->params[$cName]["suffix"] = Utility::get($cParam,"suffix","");			
		}
		return $metaData;
	}
	
	protected function onInput($data)
	{
		//Process data here
		foreach($this->params as $cName=>$cParam)
		{
			$numberFormat = array_merge($cParam,array("type"=>"number"));
			
			$from = Utility::format(floor($data[$cName]/$cParam["step"])*$cParam["step"],$numberFormat);
			$to = Utility::format(ceil($data[$cName]/$cParam["step"])*$cParam["step"],$numberFormat);
			$data[$cName] = Utility::strReplace($cParam["formatString"],array(
				"{from}"=>$from,
				"{to}"=>$to
			));
		}
		$this->next($data);
	}
}

