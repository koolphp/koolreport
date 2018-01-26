<?php
/**
 * This file contains Table widget
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

// "columns"=>array(
// 	"type",
// 	"{others}"=>array(
// 		"type"=>"number",
// 		""=>"" //Expression or function
// 	)
// )


namespace koolreport\widgets\koolphp;
use \koolreport\core\Widget;
use \koolreport\core\Utility;
use \koolreport\core\DataStore;
use \koolreport\core\Process;
use \koolreport\core\DataSource;

class Table extends Widget
{
	protected $name;
	protected $columns;
	protected $cssClass;
    protected $removeDuplicate;
	protected $excludedColumns;
	protected $formatFunction;
	
	protected $showFooter;
	protected $showHeader;
	protected $footer;

	protected $paging;

	protected $clientEvents;

	protected $headers;

	protected function resourceSettings()
	{
		return array(
			"library"=>array("jQuery"),
			"folder"=>"table",
			"js"=>array("table.js"),
			"css"=>array("table.css"),
		);
	}

	protected function onInit()
	{	
		$this->useLanguage();
		$this->useDataSource();
		$this->useAutoName("ktable");
		$this->clientEvents = Utility::get($this->params,"clientEvents");
		$this->columns = Utility::get($this->params,"columns",array());
		
		if($this->dataStore==null)
		{
			$data = Utility::get($this->params,"data");
			if(is_array($data))
			{
				if(count($data)>0)
				{
					$this->dataStore = new DataStore;
					$this->dataStore->data($data);
					$row = $data[0];
					$meta = array("columns"=>array());
					foreach($row as $cKey=>$cValue)
					{
						$meta["columns"][$cKey] = array(
							"type"=>Utility::guessType($cValue),
						);
					}
					$this->dataStore->meta($meta);	
				}
				else
				{
					$this->dataStore = new DataStore;
					$this->dataStore->data(array());
					$metaColumns = array();
					foreach($this->columns as $cKey=>$cValue)
					{
						if(gettype($cValue)=="array")
						{
							$metaColumns[$cKey] = $cValue;
						}
						else
						{
							$metaColumns[$cValue] = array();
						}
					}
					$this->dataStore->meta(array("columns"=>$metaColumns));
				}	
			}
			if($this->dataStore==null)
			{
				throw new \Exception("dataSource is required for Table");
				return;	
			}
		}

		if($this->dataStore->countData()==0 && count($this->dataStore->meta()["columns"])==0)
		{
			$meta = array("columns"=>array());
			foreach($this->columns as $cKey=>$cValue)
			{
				if(gettype($cValue)=="array")
				{
					$meta["columns"][$cKey] = $cValue;
				}
				else
				{
					$meta["columns"][$cValue] = array();
				}
			}
			$this->dataStore->meta($meta);
		}

		$this->removeDuplicate = Utility::get($this->params,"removeDuplicate",array());
		$this->cssClass = Utility::get($this->params,"cssClass",array());
		$this->excludedColumns = Utility::get($this->params,"excludedColumns",array());

		$this->showFooter = Utility::get($this->params,"showFooter");
		$this->showHeader = Utility::get($this->params,"showHeader",true);
		

		$this->paging = Utility::get($this->params,"paging");
		if($this->paging!==null)
		{
			$this->paging = array(
				"pageSize"=>Utility::get($this->paging,"pageSize",10),
				"pageIndex"=>Utility::get($this->paging,"pageIndex",0),
				"align"=>Utility::get($this->paging,"align","left"),
			);
			$this->paging["itemCount"]=$this->dataStore->countData();
			$this->paging["pageCount"]=ceil($this->paging["itemCount"]/$this->paging["pageSize"]);
		}

		//Header Group
		$this->headers = Utility::get($this->params,"headers",array());
	}

	protected function formatValue($value,$format,$row=null)
	{
        $formatValue = Utility::get($format,"formatValue",null);

        if(is_string($formatValue))
        {
            eval('$fv="'.str_replace('@value','$value',$formatValue).'";');
            return $fv;
        }
        else if(is_callable($formatValue))
        {
            return $formatValue($value,$row);
        }
		else
		{
			return Utility::format($value,$format);
		}
	}

	public function onRender()
	{

        $meta = $this->dataStore->meta();
        $showColumnKeys = array();
        
        if($this->columns==array())
        {
            $this->dataStore->popStart();
			$row = $this->dataStore->pop();
			if($row)
			{
				$showColumnKeys = array_keys($row);
			}
			else if(count($meta["columns"])>0)
			{
				$showColumnKeys = array_keys($meta["columns"]);
			}
        }
        else
        {
            foreach($this->columns as $cKey=>$cValue)
            {

				if($cKey==="{others}")
				{
					$this->dataStore->popStart();
					$row = $this->dataStore->pop();
					$allKeys = array_keys($row);
					foreach($allKeys as $k)
					{
						if(!in_array($k,$showColumnKeys))
						{
							$meta["columns"][$k] = array_merge($meta["columns"][$k],$cValue);
							array_push($showColumnKeys,$k);
						}
					}
				}
				else
				{
					if(gettype($cValue)=="array")
					{
						if($cKey==="#")
						{
							$meta["columns"][$cKey] = array(
								"type"=>"number",
								"label"=>"#",
								"start"=>1,
							);
						}

						$meta["columns"][$cKey] =  array_merge($meta["columns"][$cKey],$cValue);                
						if(!in_array($cKey,$showColumnKeys))
						{
							array_push($showColumnKeys,$cKey);
						}
					}
					else
					{
						if($cValue==="#")
						{
							$meta["columns"][$cValue] = array(
								"type"=>"number",
								"label"=>"#",
								"start"=>1,
							);
						}
						if(!in_array($cValue,$showColumnKeys))
						{
							array_push($showColumnKeys,$cValue);
						}
					}

				}
            }            
        }

		$cleanColumnKeys = array();
		foreach($showColumnKeys as $key)
		{
			if(!in_array($key,$this->excludedColumns))
			{
				array_push($cleanColumnKeys,$key);
			}
		}
		$showColumnKeys = $cleanColumnKeys;

		
		//Remove Duplicate
        $span = null;
		$groupColumns = $this->removeDuplicate;
            
        if($groupColumns!=array())
        {
            $span = array();
            $dup = array();
            $this->dataStore->popStart();
			while($row=$this->dataStore->pop())
			{
			    $i = $this->dataStore->getPopIndex();
				$sRow = array();
				for($j=0;$j<count($groupColumns);$j++)
				{
					$gColumn = $groupColumns[$j];
					if(!isset($dup[$gColumn]))
					{
						$dup[$gColumn] = array(
							"firstIndex"=>$i,
							"value"=>$row[$gColumn]
						);
						$sRow[$gColumn] = 1;
					}
					else
					{
						if($row[$gColumn] == $dup[$gColumn]["value"])
						{
							if(isset($groupColumns[$j-1]) && isset($span[$i][$groupColumns[$j-1]]) && $span[$i][$groupColumns[$j-1]]==1)
							{
								$sRow[$gColumn] = 1;
								$dup[$gColumn]["value"] = $row[$gColumn];
								$dup[$gColumn]["firstIndex"] = $i;								
							}
							else
							{
								$span[$dup[$gColumn]["firstIndex"]][$gColumn]++;
								$sRow[$gColumn] = 0;								
							}
						}
						else
						{
							$sRow[$gColumn] = 1;
							$dup[$gColumn]["value"] = $row[$gColumn];
							$dup[$gColumn]["firstIndex"] = $i;
						}
					}
				}
				array_push($span,$sRow);
			}
		}

		if($this->showFooter)
		{
			$this->footer = array();
			foreach($showColumnKeys as $cKey)
			{
				$storage[$cKey]=null;
			}
			
			$this->dataStore->popStart();
			while($row = $this->dataStore->pop())
			{
				foreach($showColumnKeys as $cKey)
				{
					$method = Utility::get($meta["columns"][$cKey],"footer");
					if($method!==null)
					{
						switch(strtolower($method))
						{
							case "sum":
							case "avg":
								if($storage[$cKey]===null)
								{
									$storage[$cKey] = 0;
								}
								$storage[$cKey]+=$row[$cKey];
							break;
							case "min":
								if($storage[$cKey]===null)
								{
									$storage[$cKey] = INF;
								}
								if($storage[$cKey]>$row[$cKey])
								{
									$storage[$cKey]=$row[$cKey];
								}
							break;
							case "max":
								if($storage[$cKey]===null)
								{
									$storage[$cKey] = -INF;
								}
								if($storage[$cKey]<$row[$cKey])
								{
									$storage[$cKey]=$row[$cKey];
								}
							break;
						}
					}
				}
			}
			foreach($showColumnKeys as $cKey)
			{
				$method = Utility::get($meta["columns"][$cKey],"footer");
				switch(strtolower($method))
				{
					case "sum":
					case "min":
					case "max":
						$this->footer[$cKey] = $storage[$cKey];	
					break;
					case "avg":
						$this->footer[$cKey] = $storage[$cKey]/$this->dataStore->countData();
					break;
					case "count":
						$this->footer[$cKey] = $this->dataStore->countData();
					break;
				}
			}
		}
		
		
		//Prepare data
		$this->template("Table",array(
			"showColumnKeys"=>$showColumnKeys,
			"span"=>$span,
			"meta"=>$meta,
		));
	}	

}