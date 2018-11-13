<?php
/**
 * This file cotnains process to group data like GROUP BY in SQL
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * ->pipe(new Group(array(
 * 		"by"=>"customerName",
 * 		"sum"=>"dollar_sales",
 * 		"sort"=>true
 * )))
 * If a column is not specified, default min value
 */
namespace koolreport\processes;
use \koolreport\core\Process;
use \koolreport\core\Utility; 

class Group extends Process
{
	protected $groupColumns;
	protected $sumColumns;
	protected $countColumns;
	protected $avgColumns;
	protected $minColumns;
	protected $maxColumns;
	protected $sort;
	
	protected $gData;
	protected $cData;//For average


	protected function parseGroups($params)
	{
		if($params==null) 
			return array();
		if(is_array($params))
			return $params;
		if(is_string($params))
		{
			$list = explode(",",$params);
			foreach($list as &$item)
			{
				$item = trim($item);
			}
			return $list;
		}
		return array();
	}

	protected  function onInit()
	{
		$this->groupColumns = $this->parseGroups(Utility::get($this->params,"by"));
		$this->sumColumns = $this->parseGroups(Utility::get($this->params,"sum"));
		$this->countColumns = $this->parseGroups(Utility::get($this->params,"count"));
		$this->avgColumns = $this->parseGroups(Utility::get($this->params,"avg"));
		$this->minColumns = $this->parseGroups(Utility::get($this->params,"min"));
		$this->maxColumns = $this->parseGroups(Utility::get($this->params,"max"));
		
		$this->sort = Utility::get($this->params,"sort",true);
		$this->gData = array();
		$this->cData = array();
	}
	
	protected function onMetaReceived($metaData)
	{
		foreach($this->groupColumns as $column)
		{
			$metaData["columns"][$column]["method"] = "group";
		}
		foreach($this->sumColumns as $column)
		{

			$metaData["columns"][$column]["method"] = "sum";
		}
		foreach($this->countColumns as $column)
		{
			$metaData["columns"][$column]["method"] = "count";
			$metaData["columns"][$column]["type"] = "number";
		}
		foreach($this->avgColumns as $column)
		{
			$metaData["columns"][$column]["method"] = "avg";
		}
		
		foreach($this->minColumns as $column)
		{
			$metaData["columns"][$column]["method"] = "min";
		}
		foreach($this->maxColumns as $column)
		{
			$metaData["columns"][$column]["method"] = "max";
		}
		return $metaData;
	}

	protected function onInput($row)
	{
		$index = "";
		foreach($this->groupColumns as $gColumn)
		{
			$index.=$row[$gColumn];
		}
		$index = strtolower($index).md5($index);
		if(isset($this->gData[$index]))
		{
			$res = $this->gData[$index];
			$this->cData[$index]++;
			
			foreach($this->sumColumns as $sumColumn)
			{
				$res[$sumColumn]+=$row[$sumColumn];
			}
			foreach($this->countColumns as $countColumn)
			{
				$res[$countColumn]=$this->cData[$index];
			}
			foreach($this->minColumns as $minColumn)
			{
				if($res[$minColumn]>$row[$minColumn])
				{
					$res[$minColumn]=$row[$minColumn];	
				}
			}
			
			
			foreach($this->maxColumns as $maxColumn)
			{
				if($res[$maxColumn]<$row[$maxColumn])
				{
					$res[$maxColumn]=$row[$maxColumn];					
				}
			}
			
			
			foreach($this->avgColumns as $avgColumn)
			{
				$res[$avgColumn] = ($res[$avgColumn]*($this->cData[$index]-1)+$row[$avgColumn])/$this->cData[$index];
			}
			
			$this->gData[$index] = $res;
		}
		else
		{
			$this->cData[$index] = 1;
			foreach($this->countColumns as $countColumn)
			{
				$row[$countColumn]=1;
			}
			$this->gData[$index] = $row;
		}
	}
	
	protected function onInputEnd()
	{
		if($this->sort)
		{
			ksort($this->gData,SORT_STRING);			
		}
		foreach($this->gData as $index=>$data)
		{
			$this->next($data);	
		}
	}
}