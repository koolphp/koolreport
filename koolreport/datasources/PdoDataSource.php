<?php
/**
 * This file contain class to handle pulling data from MySQL, Oracle, SQL Server and many others.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\datasources;
use \koolreport\core\DataSource;
use \koolreport\core\Utility;
use PDO;

class PdoDataSource extends DataSource
{
	static $connections;
	protected $connection;
	protected $query;
	protected $params;
	protected function onInit()
	{		
		$connectionString = Utility::get($this->params,"connectionString","");
		$username = Utility::get($this->params,"username","");
		$password = Utility::get($this->params,"password","");
		$charset = Utility::get($this->params,"charset");
		
		$key = md5($connectionString.$username.$password);
		if(PdoDataSource::$connections==null)
		{
			PdoDataSource::$connections = array();
		}
		if(isset(PdoDataSource::$connections[$key]))
		{
			$this->connection = PdoDataSource::$connections[$key];
		}
		else 
		{
			$this->connection = new PDO($connectionString,$username,$password);
			PdoDataSource::$connections[$key] = $this->connection;
		}
		if($charset)
		{
			$this->connection->exec("set names $charset");
		}
	}
	
	public function query($query,$params=null)
	{
		$this->query = $query;
		if($params!=null)
		{
			$this->params = $params;
		}
		return $this;
	}

	public function params($params)
	{
		$this->params = $params;
		return $this;
	}
	
	protected function bindParams($query,$params)
	{
		if($params!=null)
		{
			foreach($params as $key=>$value)
			{
				if(gettype($value)==="array")
				{
					$value = '"'.implode('","',$value).'"';
					$query = str_replace($key,$value,$query);
				}
				else
				{
					$query = str_replace($key,"\"$value\"",$query);
				}
			}
		}
		return $query;
	}

	protected function guessType($native_type)
	{
		$map = array(
			"character"=>"string",
			"char"=>"string",
			"string"=>"string",
			"str"=>"string",
			"text"=>"string",
			"blob"=>"string",
			"binary"=>"string",
			"enum"=>"string",
			"set"=>"string",
			"int"=>"number",
			"double"=>"number",
			"float"=>"number",
			"long"=>"number",
			"numeric"=>"number",
			"decimal"=>"number",
			"real"=>"number",
			"bit"=>"number",
			"boolean"=>"number",
			"datetime"=>"datetime",
			"date"=>"datetime",
			"time"=>"datetime",
			"year"=>"datetime",
		);
		
		$native_type = strtolower($native_type);
		
		foreach($map as $key=>$value)
		{
			if(strpos($native_type,$key)!==false)
			{
				return $value;
			}			
		}
		return "unknown";
	}
	
	public function start()
	{
		
		$query = $this->bindParams($this->query,$this->params);
		$stm = $this->connection->prepare($query);
		$stm->execute();

		$metaData = array("columns"=>array());
		$numcols = $stm->columnCount();
		for($i=0;$i<$numcols;$i++)
		{
			$info = $stm->getColumnMeta($i);
			$metaData["columns"][$info["name"]] = array(
				"type"=>$this->guessType($info["native_type"]),
			);
		}
				
		$this->sendMeta($metaData,$this);
		$this->startInput(null);
		
		$numberColumnList = array();
		foreach($metaData["columns"] as $cName=>$cMeta)
		{
			if($cMeta["type"]=="number")
			{
				array_push($numberColumnList,$cName);
			}
		}
						
		
		while($row=$stm->fetch(PDO::FETCH_ASSOC))
		{
			foreach($numberColumnList as $cName)
			{
					$row[$cName]+=0;
			}
			$this->next($row,$this);
		}			
		$this->endInput(null);
	}
}
