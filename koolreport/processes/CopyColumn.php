<?php
/* Usage
 * ->pipe(new CopyColumn(array(
 * 		"amount"=>"amountCopy",
 * 		"time"=>"timeCopy",
 * )))
 * Create a new column with new name with same value
 * 
 */
namespace koolreport\processes;
use \koolreport\core\Utility;
use \koolreport\core\Process;
 
class CopyColumn extends Process
{
	protected function onMetaReceived($metaData)
	{
		foreach($this->params as $original=>$copy)
		{
			$metaData["columns"][$copy] = $metaData["columns"][$original];
		}
		return $metaData;
	}
	
	public function onInput($data)
	{
		//Process data here
		foreach($this->params as $original=>$copy)
		{
			$data[$copy] = $data[$original];
		}
		$this->next($data);
	}
}

