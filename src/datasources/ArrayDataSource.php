<?php
/**
 * This file contains base class to pull data from array
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\datasources;
use \koolreport\core\DataSource;
use \koolreport\core\Utility;


class ArrayDataSource extends DataSource
{	
	/**
	 * @var string $dataFormat Either "table" format or "associate" format
	 */
	protected $dataFormat="associate";//"table"|"associate"	

	/**
	 * @var array $data Containing data
	 */
	protected $data;

	protected function onInit()
	{
		$this->dataFormat = trim(strtolower(Utility::get($this->params,"dataFormat","associate")));
	}
	
	/**
	 * Guess type of a value
	 * 
	 * @param mixed $value The value
	 * @return string Type of value
	 */
	protected function guessType($value)
	{
		$map = array(
			"float"=>"number",
			"double"=>"number",
			"int"=>"number",
			"integer"=>"number",
			"bool"=>"number",
			"numeric"=>"number",
			"string"=>"string",
		);

		$type = strtolower(gettype($value));
		foreach($map as $key=>$value)
		{
			if(strpos($type,$key)!==false)
			{
				return $value;
			}			
		}
		return "unknown";
	}

	/**
	 * Load data
	 * 
	 * The data is loaded in form of "table" or "associate" of your choice
	 * 
	 * @param array $data Data
	 * @param string $dataFormat Either "table" or "associate"
	 * @return ArrayDataSource This datasource object
	 */
	public function load($data,$dataFormat=null)
	{
		if($dataFormat)
		{
			$this->dataFormat = $dataFormat;
		}
		$this->params["data"] = $data;
		return $this;
	}

	/**
	 * Start piping data
	 */
	public function start()
	{
		$data = Utility::get($this->params,"data",array());
		if($data && count($data)>0)
		{	
			switch($this->dataFormat)
			{
				case "table":
					$columnNames = $data[0];
					$metaData = array("columns"=>array());
					for($i=0;$i<count($columnNames);$i++)
					{						
						$metaData["columns"][$columnNames[$i]] = array(
							"type"=>(isset($data[1]))?$this->guessType($data[1][$i]):"unknown",
						);;
					}					
					$this->sendMeta($metaData,$this);
					$this->startInput(null);
					$rowNum = count($data);
					for($i=1;$i<$rowNum;$i++)
					{
						$this->next(array_combine($columnNames, $data[$i]),$this);	
					}
					break;
				case "associate":
				default:
					$metaData = array("columns"=>array());
					foreach($data[0] as $key=>$value)
					{
						$metaData["columns"][$key]=array(
							"type"=>$this->guessType($value),
						);
					}
					$this->sendMeta($metaData,$this);
					$this->startInput(null);			
					foreach($data as $row)
					{
						$this->next($row);
					}				
					break;
			}
		}
		$this->endInput(null);
	}
}