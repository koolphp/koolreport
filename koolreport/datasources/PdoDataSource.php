<?php
/**
 * This file contain class to handle pulling data from MySQL, Oracle, SQL Server and many others.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 * 
 * 
    For Pdo with Oracle for Apache on Windows:
    - Install Oracle database 32 bit only (php on Windows is only 32 bit).
    - Download and extract Oracle Instant Client 32 bit, add the extracted folder 
    to Windows' Path environment variable.
    - Enable extension=php_pdo_oci.dll in php.ini.
	- Restart Apache.

	"pdoOracle"=>array(
        'connectionString' => 'oci:dbname=//localhost:1521/XE',
        'username' => 'sa',
        'password' => 'root',
        'class' => "\koolreport\datasources\PdoDataSource",
    ),
	
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
		// $this->connection = Utility::get($this->params,"connection",null);
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

	protected function prepareParams($query, $sqlParams) {
		$resultQuery = $query;
		if (empty($sqlParams)) return $resultQuery;
		foreach ($sqlParams as $paName => $paValue) {
			if(gettype($paValue)==="array")
			{
				$paramList = [];
				foreach ($paValue as $i=>$value)
					$paramList[] = $paName . "_param$i";
				$resultQuery = str_replace($paName,implode(",", $paramList),$query);
			}
		}
		return $resultQuery;
	}

	protected function typeToPDOParamType($type) {
		switch ($type) {
			case "boolean":
				return PDO::PARAM_BOOL;
			case "integer":
				return PDO::PARAM_STR;
			case "NULL":
				return PDO::PARAM_NULL;
			case "resource":
				return PDO::PARAM_LOB;
			case "double":
			case "string":
			default:
				return PDO::PARAM_STR;
		}
	}

	protected function bindParams($stm, $sqlParams) {
		if (empty($sqlParams)) return null;
		foreach ($sqlParams as $paName => $paValue) {
			$type = gettype($paValue);
			if ($type === 'array') {
				foreach ($paValue as $i=>$value) {
					$paramType = $this->typeToPDOParamType(gettype($value));
					$stm->bindValue($paName . "_param$i", $value, $paramType);
				}
			} else {
				$paramType = $this->typeToPDOParamType($type);
				$stm->bindValue($paName, $paValue, $paramType);
			}
		}
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

	protected function guessTypeFromValue($value)
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
	
	public function start()
	{
		$query = $this->prepareParams($this->query,$this->sqlParams);
		$stm = $this->connection->prepare($query);
		$this->bindParams($stm,$this->sqlParams);
		$stm->execute();

		$error = $stm->errorInfo();
		if($error[2]!=null)
		{
			throw new \Exception("Query Error >> [".$error[2]."] >> $query");
			return;
		}

		$driver = strtolower($this->connection->getAttribute(PDO::ATTR_DRIVER_NAME));
		$metaSupportDrivers = array('dblib', 'mysql', 'pgsql', 'sqlite');
		$metaSupport = false;
		foreach ($metaSupportDrivers as $supportDriver)
			if (strpos($driver, $supportDriver) !== false)
				$metaSupport = true;
		if (! $metaSupport) {
			$row = $stm->fetch(PDO::FETCH_ASSOC);
			$cNames = empty($row) ? array() : array_keys($row);
			$numcols = count($cNames);
		}
		else {
			$numcols = $stm->columnCount();
		}

		$metaData = array("columns"=>array());
		for($i=0;$i<$numcols;$i++)
		{
			if (! $metaSupport) {
				$cName = $cNames[$i];
				$cType = $this->guessTypeFromValue($row[$cName]);
			}
			else {
				$info = $stm->getColumnMeta($i);
				$cName = $info["name"];
				$cType = $this->guessType($info["native_type"]);

			}
			$metaData["columns"][$cName] = array(
				"type"=>$cType,
			);
			switch($cType)
			{
				case "datetime":
					$metaData["columns"][$cName]["format"] = "Y-m-d H:i:s";
					break;
				case "date":
					$metaData["columns"][$cName]["format"] = "Y-m-d";
					break;
				case "time":
					$metaData["columns"][$cName]["format"] = "H:i:s";
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
						
		if (! isset($row))
			$row=$stm->fetch(PDO::FETCH_ASSOC);
		while($row)
		{
			foreach($numberColumnList as $cName)
			{
					$row[$cName]+=0;
			}
			$this->next($row,$this);
			$row=$stm->fetch(PDO::FETCH_ASSOC);
		}			
		$this->endInput(null);
	}
}
