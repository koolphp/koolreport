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

	protected $paging;

	protected $clientEvents;

	protected $headers;
	protected $responsive;

	protected $group;

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
		$this->responsive = Utility::get($this->params,"responsive",false);

		$group = Utility::get($this->params,"grouping");
		if($group)
		{
			$this->group = array();
			foreach($group as $cKey=>$cValue)
			{
				if(gettype($cValue)=="array")
				{
					$this->group[$cKey] = $cValue;
				}
				else if (gettype($cValue)=="string")
				{
					$this->group[$cValue] = array(
						"top"=>"<strong>{".$cValue."}</strong>"
					);
				}
			}
		}
	}

	static function formatValue($value,$format,$row=null)
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

	protected function generateGroups($meta)
	{
		if($this->group)
		{
			$result = array();

			$sorts = array();
			foreach($this->group as $by=>$settings)
			{
				$sorts[$by] = Utility::get($settings,"sort","asc");
			}
			$this->dataStore->sort($sorts);
			function groupLevel($meta,$groupModel,$store,&$result,$level=0,$start=0,$previousParams=array())
			{
				$keys = array_keys($groupModel); 
				$store->breakGroup($keys[$level],function($store,$localStart) use ($meta,$groupModel,&$result,$keys,$level,$start,$previousParams){
					$by = $keys[$level];
					$agroup = array_merge($previousParams,array(
						"{".$by."}"=>$store->get(0,$by),
						"{count}"=>$store->count(),
					));
					$previousParams["{".$by."}"] = $agroup["{".$by."}"];
					$calculate = Utility::get($groupModel[$by],"calculate",array());
					foreach($calculate as $paramName=>$def)
					{
						if(is_array($def))
						{
							$method = strtolower($def[0]);
							if(in_array($method,array("sum","count","min","max","mode")))
							{
								$agroup[$paramName] = Table::formatValue($store->$method($def[1]),$meta["columns"][$def[1]]);
							}
							
						}
						else if(is_callable($def))
						{
							$agroup[$paramName] = $def($store);
						}
					}
					$startTemplate = Utility::get($groupModel[$by],"top");
					$endTemplate = Utility::get($groupModel[$by],"bottom");

					if($startTemplate)
					{
						if(!isset($result[$start+$localStart]))
						{
							$result[$start+$localStart] = array();
						}
						array_push($result[$start+$localStart],array(
							$start+$localStart,
							$start+$localStart+$agroup["{count}"],
							Utility::strReplace($startTemplate,$agroup),
						));
					}
					if($endTemplate)
					{
						if(!isset($result[$start+$localStart+$agroup["{count}"]]))
						{
							$result[$start+$localStart+$agroup["{count}"]] = array();
						}
						array_unshift($result[$start+$localStart+$agroup["{count}"]],array(
							$start+$localStart,
							$start+$localStart+$agroup["{count}"],
							Utility::strReplace($endTemplate,$agroup),
						));						
					}
					if($level<count($keys)-1)
					{
						groupLevel($meta,$groupModel,$store,$result,$level+1,$start+$localStart,$previousParams);
					}
				});
			}
			groupLevel($meta,$this->group,$this->dataStore,$result);
			return $result;
		}
		return false;
	}
	protected function renderRowGroup($groups,$index,$colspan)
	{
		if($groups && isset($groups[$index]))
		{
			foreach($groups[$index] as $grow)
			{
				echo "<tr from='$grow[0]' to='$grow[1]' class='row-group' style='display:none;'>";
				if(strpos($grow[2],"<td")===0)
				{
					echo $grow[2];
				}
				else
				{
					echo "<td colspan='$colspan'>$grow[2]</td>";	
				}
				echo "</tr>";
			}
		}
	}
	public function onRender()
	{

        $meta = $this->dataStore->meta();
        $showColumnKeys = array();
        
        if($this->columns==array())
        {
			if($row = $this->dataStore[0])
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
					$allKeys = array_keys($this->dataStore[0]);
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
		
		//Prepare data
		$this->template("Table",array(
			"showColumnKeys"=>$showColumnKeys,
			"meta"=>$meta,
		));
	}
}