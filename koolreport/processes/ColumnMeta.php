<?php
/**
 * This file contain process to set meta data for column.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * ->pipe(new ColumnMeta(array(
 * 		"amount"=>array(
 * 			"name"=>"sale_amount"
 * 			"type"=>"number",
 * 			"label"=>"Amount",
 * 		),
 * 		"{override}"=>false
 * )))
 * Create a new column with new name with same value
 * the {override} is directive to guide ColumnMeta to overide column or just add more information to the meta.
 */
namespace koolreport\processes;
use \koolreport\core\Utility;
use \koolreport\core\Process;
 
class ColumnMeta extends Process
{
	
	protected $override;
	
	protected function onInit()
	{
		$this->override = Utility::get($this->params,"{override}",false);
	}
	
	protected function onMetaReceived($metaData)
	{
		foreach($this->params as $columnName=>$columnInfo)
		{
			if(isset($metaData["columns"][$columnName]))
			{
				$newColumnName = Utility::get($columnInfo,"name");
				$currentColumnInfo = $metaData["columns"][$columnName];
				if($newColumnName)
				{
					unset($columnInfo["name"]);
					unset($metaData["columns"][$columnName]);
				}
				
				if($this->override)
				{
					$metaData["columns"][($newColumnName)?$newColumnName:$columnName] = $columnInfo;
				}
				else
				{
					$metaData["columns"][($newColumnName)?$newColumnName:$columnName] = array_merge($currentColumnInfo,$columnInfo);						
				}
			}
		}
		return $metaData;
	}
	
	
	
	protected function onInput($data)
	{
		foreach($this->params as $columnName=>$columnInfo)
		{
			if(isset($columnInfo["name"]))
			{
				$columnValue = $data[$columnName];
				unset($data[$columnName]);
				$data[$columnInfo["name"]]=$columnValue;
			}
		}
		$this->next($data);
	}
}

