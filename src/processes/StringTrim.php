<?php
/**
 * This file contains class that help to remove space or unwanted character at the beginning and end of data cell.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * ->pipe(new StringTrim(array(
 * 		"name","address",
 * 		"character_mask"=>"\t\n\r\0\x0B"
 * )))
 * ->pipe(new StringTrim()): Trim all possible column
 * ->pipe(new StringTrim(array("name","address"))): trim space by default if without character mask
 * 
 */
namespace koolreport\processes;
use \koolreport\core\Process;

class StringTrim extends Process
{
	protected $character_mask;	
	protected function onInit()
	{
		if(isset($this->params["character_mask"]))
		{
			$this->character_mask = $this->params["character_mask"];
			unset($this->params["character_mask"]);
		}	
	}
	
	protected function onInput($data)
	{
		//Process data here
		foreach($this->params as $column)
		{
			if($this->character_mask)
			{
				$data[$column] = trim($data[$column],$this->character_mask);
			}
			else
			{
				$data[$column] = trim($data[$column]);				
			}
		}
		$this->next($data);
	}
}