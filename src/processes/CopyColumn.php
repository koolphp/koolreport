<?php
/**
 * This file contains process to copy a column including data and meta data.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * ->pipe(new CopyColumn(array(
 * 		"amountCopy"=>"amount",
 * 		"timeCopy"=>"time",
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
		foreach($this->params as $copy=>$original)
		{
			$metaData["columns"][$copy] = $metaData["columns"][$original];
		}
		return $metaData;
	}
	
	public function onInput($data)
	{
		//Process data here
		foreach($this->params as $copy=>$original)
		{
			$data[$copy] = $data[$original];
		}
		$this->next($data);
	}
}

