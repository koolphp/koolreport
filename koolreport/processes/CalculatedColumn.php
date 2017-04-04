<?php
/* Usage
 * ->pipe(new CalculatedColumn(array(
 * 		"total"=>"{price}*{unit}",
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
	
	protected function onMetaReceived($metaData)
	{
		$new_columns = array_keys($this->params);
		foreach($new_columns as $newcol)
		{
			if(!isset($metaData["columns"][$newcol]))
			{
				$metaData["columns"][$newcol] = array("type"=>"number");
			}
		}	
		return $metaData;
	}
	
	protected function onInput($data)
	{
		//Process data here
		foreach($this->params as $column=>$expression)
		{
			foreach($data as $k=>$v)
			{
				$expression = str_replace("{".$k."}",$v,$expression);
			}
			eval('$data[$column]='.$expression.';');
		}
		$this->next($data);
	}
}