<?php
/**
 * This file contains class to join two data flow on a condition.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * (new Join(array($source1,source2,array("id1"=>"id2"))))
 * 
 * 
 */
namespace koolreport\processes;
use \koolreport\core\Process;
use \koolreport\core\Utility; 

class Join extends Process
{
	protected $container;	
	
	public function __construct($sourceOne,$sourceTwo,$link)
	{
		$this->container = array(
			array(
				"source"=>$sourceOne,
				"keys"=>array_keys($link),
				"meta"=>null,
				"data"=>array(),	
			),		
			array(
				"source"=>$sourceTwo,
				"keys"=>array_values($link),
				"meta"=>null,
				"data"=>array(),
			)
		);
		parent::__construct();
		$sourceOne->pipe($this);
		$sourceTwo->pipe($this);
	}
	
	
	public function input($row,$source)
	{
		
		for( $i=0;$i<count($this->container);$i++)
		{
			if($this->container[$i]["source"]===$source)
			{
				$key = "";
				foreach($this->container[$i]["keys"] as $cName)
				{
					$key.=$row[$cName];
				}
				if(!isset($this->container[$i]['data'][$key]))
				{
					$this->container[$i]['data'][$key] = array($row);
				}
				else
				{
					array_push($this->container[$i]['data'][$key],$row);
				}
				break;
			}
		}
	}
	protected function onInputEnd()
	{
		foreach($this->container[0]["data"] as $key=>$rows)
		{
			if(isset($this->container[1]["data"][$key]))
			{
				foreach($rows as $first)
				{
					foreach($this->container[1]["data"][$key] as $second)
					{
						$this->next(array_merge($first,$second));
					}
				}
			}
		}		
	}

	public function receiveMeta($metaData,$source)
	{
		if($source===$this->container[0]["source"])
		{
			$this->container[0]["meta"] = $metaData;
		}
		else
		{
			$this->container[1]["meta"] = $metaData;
		}
		if($this->container[0]["meta"] && $this->container[1]["meta"])
		{
			$meta = array(
				"columns"=>array_merge($this->container[0]["meta"]["columns"],$this->container[1]["meta"]["columns"])
			);
			$this->sendMeta($meta);
		}
	}
}
