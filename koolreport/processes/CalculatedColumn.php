<?php
/**
 * This file contains class to generate new column from expression.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * ->pipe(new CalculatedColumn(array(
 * 		"total"=>"{price}*{unit}",
 * 		"receivedAmount"=>"{amount}-{fee}"
 * )))
 * ->pipe(new CalculatedColumn(array(
 * 		"total"=>function($data){return $data["price"]*$data["unit"]},
 * 		"receivedAmount"=>"{amount}-{fee}"
 * )))
 * ->pipe(new CalculatedColumn(array(
 * 		"total"=>array(
 *			"exp"=>"{price}*{unit}"
 *			"type"=>"number"
 *		),
 * 		"receivedAmount"=>"{amount}-{fee}"
 * )))
 * If the column is existed then replace it with new calculated value 
 * If the column is not existed, create new and put the value there.
 * Also change the metaData to reflect the new column added.
 */
namespace koolreport\processes;
use \koolreport\core\Utility;
use \koolreport\core\Process;

class CalculatedColumn extends Process
{
	
	protected function onInit()
	{
		$params = array();
		foreach($this->params as $cKey=>$cValue)
		{
			switch(gettype($cValue))
			{
				case "string":
				case "number":
				case "object":
					$params[$cKey] = array(
						"exp"=>$cValue,
						"type"=>"number",
					);
				break;
				case "array":
					if(!isset($cValue['exp']))
					{
						$cValue['exp']="'no expression'";
					}
					if(!isset($cValue['type']))
					{
						$cValue['type']="unknown";
					}
					$params[$cKey] = $cValue;
				break;
			}
		}
		$this->params = $params;	
	}

	protected function onMetaReceived($metaData)
	{
		foreach($this->params as $cKey=>$cValue)
		{
			unset($cValue["exp"]);
			$metaData["columns"][$cKey] = $cValue;
		}
		return $metaData;
	}
	
	protected function onInput($data)
	{
		foreach($this->params as $cKey=>$cValue)
		{
			switch(gettype($cValue["exp"]))
			{
				case "string":
					$expression = $cValue["exp"];
					foreach($data as $k=>$v)
					{
						$expression = str_replace("{".$k."}",$v,$expression);
					}
					eval('$data[$cKey]='.$expression.';');							
				break;
				case "object":
					$function = $cValue["exp"];
					$data[$cKey] = $function($data);
				break;
			}
		}
		$this->next($data);
	}
}