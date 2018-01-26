<?php
/**
 * This file contains definition for number range
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * ->pipe(new NumberRange(array(
 * 		"sale"=>array(
 * 			"high"=>array(50,null),
 * 			"medium"=>array(10,50),
 * 			"low"=>array(null,10)
 * 		)
 * )))
 * 
 */
namespace koolreport\processes;
use \koolreport\core\Process;
use \koolreport\core\Utility;

class NumberRange extends Process
{
	protected function onMetaReceived($metaData)
	{
		foreach($this->params as $cName=>$cParams)
		{
			$metaData["columns"][$cName] = array(
				"type"=>"string",
			);
		}
		return $metaData;		
	}
	
	protected function onInput($data)
	{
		//Process data here
		foreach($this->params as $cName=>$cParams)
		{
			$value = null;
			foreach($cParams as $cValue=>$criteria)
			{
				$from = Utility::get($criteria,0);
				$to = Utility::get($criteria,1);
				if(($from==null || $from<$data[$cName]) && ($to==null || $data[$cName]<$to))
				{
					$value=$cValue;
				}
			}
			$data[$cName] = ($value)?$value:null;
		}
		$this->next($data);
	}
}
