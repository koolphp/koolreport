<?php
/**
 * This file contains class to format the date time column
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * ->pipe(new DateTimeFormat(array(
 * 		"last_login"=>array(
 * 			"from"=>"Y-m-d H:i:s",
 * 			"to"=>"F j, Y"
 * 		),
 * 		"created_time"=>"F j,Y"
 * )))
 * 
 */
namespace koolreport\processes;
use \koolreport\core\Process;
use \koolreport\core\Utility;

class DateTimeFormat extends Process
{
	protected function onInit()
	{
		foreach($this->params as $cName=>$cParams)
		{
			if(gettype($cParams)=="string")
			{
				$this->params[$cName] = array(
					"to"=>$cParams
				);
			}
		}
	}
	
	protected function onMetaReceived($metaData)
	{
		foreach($this->params as $cName=>$cParams)
		{
			$from = Utility::get($cParams,"from");
			if(!$from)
			{
				$from = Utility::get($metaData["columns"][$cName],"format","Y-m-d H:i:s");
			}
			$to = Utility::get($cParams,"to","Y-m-d H:i:s");			
			
			$this->params[$cName] = array(
				"from"=>$from,
				"to"=>$to,
			);			
			$metaData["columns"][$cName] = array_merge($metaData["columns"][$cName],array(
				"type"=>"datetime",
				"format"=>$to
			));
		}
		return $metaData;
	}
	
	
	protected function onInput($data)
	{
		//Process data here
		foreach($this->params as $cName=>$cParams)
		{
			$from = Utility::get($cParams,"from","Y-m-d H:i:s");
			$to = Utility::get($cParams,"to","Y-m-d H:i:s");
			$data[$cName] = \DateTime::createFromFormat($from,$data[$cName])->format($to);			
		}
		$this->next($data);
	}
}