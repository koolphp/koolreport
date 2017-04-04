<?php
/* Usage
 * ->pipe(new CopyColumn(array(
 * 		"name","first_name"
 * )))
 * Create a new column with new name with same value
 * 
 */
namespace koolreport\processes;
use \koolreport\core\Utility;
use \koolreport\core\Process;
 
class RemoveColumn extends Process
{
	protected function onMetaReceived($metaData)
	{
		foreach($this->params as $column)
		{
			unset($metaData["columns"][$column]);
		}
		return $metaData;
	}
	
	protected function onInput($data)
	{
		//Process data here
		foreach($this->params as $column)
		{
			unset($data[$column]);
		}
		$this->next($data);
	}
}

