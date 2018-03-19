<?php
/**
 * This file contain class to handle pulling data from MySQL, Oracle, SQL Server and many others.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
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
	protected $sqlParams;
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
			$this->connection->exec("set names '$charset'");
		}
	}
	
	public function query($query,$sqlParams=null)
	{
		$this->query =  (string)$query;
		if($sqlParams!=null)
		{
			$this->sqlParams = $sqlParams;
		}
		return $this;
	}

	public function params($sqlParams)
	{
		$this->sqlParams = $sqlParams;
		return $this;
	}
	
	protected function bindParams($query,$sqlParams)
	{
		if($sqlParams!=null)
		{
			foreach($sqlParams as $key=>$value)
			{
				if(gettype($value)==="array")
				{
					$value = "'".implode("','",$value)."'";
					$query = str_replace($key,$value,$query);
				}
				else if(gettype($value)==="string")
				{
					$query = str_replace($key,"'$value'",$query);
				}
				else
				{
					$query = str_replace($key,$value,$query);
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
			"date"=>"date",
			"time"=>"time",
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
		$query = $this->bindParams($this->query,$this->sqlParams);
		$stm = $this->connection->prepare($query);
		$stm->execute();

		$error = $stm->errorInfo();
		if($error[2]!=null)
		{
			throw new \Exception("Query Error >> [".$error[2]."] >> $query");
			return;
		}

		$metaData = array("columns"=>array());
		$numcols = $stm->columnCount();
		for($i=0;$i<$numcols;$i++)
		{
			$info = $stm->getColumnMeta($i);
			$type = $this->guessType($info["native_type"]);
			$metaData["columns"][$info["name"]] = array(
				"type"=>$type,
			);
			switch($type)
			{
				case "datetime":
					$metaData["columns"][$info["name"]]["format"] = "Y-m-d H:i:s";
					break;
				case "date":
					$metaData["columns"][$info["name"]]["format"] = "Y-m-d";
					break;
				case "time":
					$metaData["columns"][$info["name"]]["format"] = "H:i:s";
					break;
			}
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
