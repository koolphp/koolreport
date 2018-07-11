<?php
/**
 * This file contain class that allows only some defined columns to go through.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * ->pipe(new OnlyColumn(array(
 * 		"id","name","address"
 * )))
 * Only have those columns are passed.
 * 
 */
namespace koolreport\processes;
use \koolreport\core\Utility;
use \koolreport\core\Process;
 
class OnlyColumn extends Process
{
	protected function onMetaReceived($metaData)
	{
		$meta = $metaData;
		$meta["columns"]=array();
		foreach($this->params as $colname)
		{
			if(isset($metaData["columns"][$colname]))
			{
				$meta["columns"][$colname] = $metaData["columns"][$colname];	
			}
		}
		return $meta;
	}
	
	protected function onInput($data)
	{
		$ndata = array();
		foreach($this->params as $colname)
		{
			if(isset($data[$colname]))
			{
				$ndata[$colname] = $data[$colname];
			}
		}
		
		$this->next($ndata);
	}
}

