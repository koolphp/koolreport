<?php
/**
 * This file contains foundation class for datastore
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\core;

class DataStore extends Node
{
	protected $dataset;
	protected $params;
	protected $report;
    protected $index=-1;
    
  
	public function __construct($report=null,$params=null)
	{
		parent::__construct();
		$this->report = $report;
		$this->params = $params;
		$this->dataset = array();
		$this->onInit();
	}
	
	protected function onInit()
	{
		
	}

	protected function onInput($data)
	{
		array_push($this->dataset,$data);
	}
    
    public function countData()
    {
        return count($this->dataset);
    }	
	
	public function meta($metaData=null)
	{
		if($metaData)
		{
			$this->metaData = $metaData;
			return $this;
		}
		else
		{
			return $this->metaData;
		}
	}

	public function data($dataset=null)
	{
		if($dataset)
		{
			$this->dataset = $dataset;
			return $this;
		}
		else
		{
			return $this->dataset;
		}
	}
    
    public function popStart()
    {
        // Start poping data, reset the index  to -1
        $this->index = -1;
		return $this;
    }
    public function getPopIndex()
    {
        return $this->index;
    }
    
    public function pop()
    {
        $this->index++;
        return Utility::get($this->dataset,$this->index);
	}

	public function get($index=0,$colName=null)
	{
		if(isset($this->data[$index]))
		{
			if($colName!==null)
			{
				if(isset($this->data[$index][$colName]))
				{
					return $this->data[$index][$colName];
				}
			}
			else
			{
				return $this->data[$index];
			}
		}
		return null;
	}

	public function filter($condition)
	{
		$cName = Utility::get($condition,0);
		$operator = Utility::get($condition,1);
		$value = Utility::get($condition,2);
		$optional_value = Utility::get($condition,3);

		if($cName===null||$operator===null)
		{
			throw new \Exception('dataStore->filter() requires condition in array form ($colname,$operator,$value)');
		}

		$result = array();
		$cType = $this->metaData["columns"][$cName]["type"];
		$dtFormat = null;
		if(in_array($cType,array("datetime","date","time")))
		{
			switch($cType)
			{
				case "datetime":
				$dtFormat = Utility::get($this->metaData["columns"][$cName],"format","Y-m-d H:i:s");
				break;
				case "date":
				$dtFormat = Utility::get($this->metaData["columns"][$cName],"format","Y-m-d");
				break;
				case "time":
				$dtFormat = Utility::get($this->metaData["columns"][$cName],"format","H:i:s");
				break;
			}
			$value =  \DateTime::createFromFormat($dtFormat,$value);
		}
		
		foreach($this->dataset as $row)
		{
			$columnValue = $row[$cName]; 
			if($dtFormat!==null)
			{
				$columnValue =  \DateTime::createFromFormat($dtFormat,$columnValue);
			}
			switch($operator)
			{
				case "=":
				case "==":
					if($columnValue==$value) array_push($result,$row);
				break;
				case "===":
					if($columnValue===$value) array_push($result,$row);
				break;
				case "!=":
					if($columnValue!=$value) array_push($result,$row);
				break;
				case "!==":
					if($columnValue!==$value) array_push($result,$row);
				break;
				case ">":
					if($columnValue>$value) array_push($result,$row);
				break;
				case ">=":
					if($columnValue>=$value) array_push($result,$row);
				break;
				case "<":
					if($columnValue<$value) array_push($result,$row);
				break;
				case "<=":
					if($columnValue<=$value) array_push($result,$row);
				break;
				case "contain":
					if(strpos(strtolower($columnValue),strtolower($value))!==false) array_push($result,$row);
				break;	
				case "notContain":
					if(strpos(strtolower($columnValue),strtolower($value))===false) array_push($result,$row);
				break;
				case "between":
					if($value<$columnValue && $columnValue<$optional_value) array_push($result,$row);
				break;
				case "notBetween":
					if (!($value<$columnValue && $columnValue<$optional_value)) array_push($result,$row);
				break;			
				case "in":
					if(!is_array($value)) $value = array($value);
					if(in_array($columnValue,$value)) array_push($result,$row);	
				break;
				case "notIn":
					if(!is_array($value)) $value = array($value);
					if(!in_array($columnValue,$value)) array_push($result,$row);	
				break;
				default:
					throw new \Exception("Unknown operator [$operator]");
					return $this;
				break;
			}
		}
		$ds = new DataStore($this->report);
		$ds->data($result);
		$ds->meta($this->metaData);
		return $ds;
	}

	public function top($num)
	{
		$count = $this->countData();
		$result = array();
		for($i=0;$i<$num && $i<$count;$i++)
		{
			array_push($result,$this->dataset[$i]);
		}
		$ds = new DataStore($this->report);
		$ds->meta($this->metaData);
		$ds->data($result);
		return $ds;
	}
	public function topByPercent($num)
	{
		$count = $this->countData();
		return $this->top(round($num*$count/100));
	}
	public function bottom($num)
	{
		$count = $this->countData();
		$result = array();
		$start = ($count>$num)?$count-$num:0;
		for($i=$start;$i<$count;$i++)
		{
			array_push($result,$this->dataset[$i]);
		}
		$ds = new DataStore($this->report);
		$ds->meta($this->metaData);
		$ds->data($result);
		return $ds;
	}
	public function bottomByPercent($num)
	{
		$count = $this->countData();
		return $this->bottom(round($num*$count/100));
	}

	public function sort($sorts)
	{
		usort($this->dataset, function($a, $b) use ($sorts) {
		  $cmp = 0;
		  foreach ($sorts as $sort => $direction) {
			if (is_string($direction)) {
			  $cmp = is_numeric($a[$sort]) && is_numeric($b[$sort]) ? 
				  $a[$sort] - $b[$sort] : strcmp($a[$sort], $b[$sort]);
			  $cmp = $direction === 'asc' ? $cmp : - $cmp;
			}
			else if (is_callable($direction)) 
			  $cmp = $direction($a[$sort], $b[$sort]);
			if ($cmp !== 0) break;
		  }
		  return $cmp;
		});
		return $this;
	}

	
	public function getReport()
	{
		return $this->report;
	}
}