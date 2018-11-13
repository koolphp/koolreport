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
use \koolreport\core\Utility as Util;
use PDO;

class PdoDataSource extends DataSource
{
    /**
     * @var array $connections List of available connections for reusing
     */
	static $connections;

    /**
     * @var $connection The current connection
     */
	protected $connection;
	
    /**
     * @var string $query The query
     */	
	protected $query;
	
    /**
     * @var array $sqlParams The params of query
     */
	protected $sqlParams;
	
    /**
     * @var bool $countToal Whether the total should be counted.
     */
	protected $countTotal;
	
    /**
     * @var bool $countFilter Whether the filter should be counted
     */
	protected $countFilter;
	
	protected function onInit()
	{		
		// $this->connection = Util::get($this->params,"connection",null);
		$connectionString = Util::get($this->params,"connectionString","");
		$username = Util::get($this->params,"username","");
		$password = Util::get($this->params,"password","");
		$charset = Util::get($this->params,"charset");
		
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

    /**
     * Set the query and params
     * 
     * @param string $query The SQL query statement
     * @param array $sqlParams The parameters of SQL query
     * @return PdoDataSource This datasource object
     */
	public function query($query,$sqlParams=null)
	{
		$this->query =  (string)$query;
		if($sqlParams!=null)
		{
			$this->sqlParams = $sqlParams;
		}
		return $this;
	}

    /**
     * Transform query
     * 
     * @param array $queryParams Parameters of query 
     */    
	public function queryProcessing($queryParams) 
    {
		$driver = strtolower($this->connection->getAttribute(PDO::ATTR_DRIVER_NAME));
		//drivers = Array ( [0] => mysql [1] => oci [2] => pgsql [3] => sqlite [4] => sqlsrv )
		switch ($driver) {
			case 'mysql':
				list($this->query, $this->totalQuery, $this->filterQuery) =
					MySQLDataSource::processQuery($this->query, $queryParams);
				break;
			case 'oci':
				list($this->query, $this->totalQuery, $this->filterQuery) =
					OracleDataSource::processQuery($this->query, $queryParams);
				break;
			case 'pgsql':
				list($this->query, $this->totalQuery, $this->filterQuery) =
					PostgreSQLDataSource::processQuery($this->query, $queryParams);			
				break;
			case 'sqlsrv':
				list($this->query, $this->totalQuery, $this->filterQuery) =
					SQLSRVDataSource::processQuery($this->query, $queryParams);
				break;
			default:
				break;
		}
		$this->countTotal = Util::get($queryParams, 'countTotal', false);
        $this->countFilter = Util::get($queryParams, 'countFilter', false);
        return $this;
    }

    /**
     * Insert params for query
     * 
     * @param array $sqlParams The parameters for query
     * @return OracleDataSource This datasource
     */  
	public function params($sqlParams)
	{
		$this->sqlParams = $sqlParams;
		return $this;
	}

    /**
     * Prepare SQL statement
     * 
     * @param string $query Query need to bind params
     * @param array $sqlParams The parameters will be bound to query
     * @return string Procesed query 
     */	
	protected function prepareParams($query, $sqlParams) {
		if (empty($sqlParams)) $sqlParams = [];
		uksort($sqlParams, function($k1, $k2) {
			return strlen($k1) < strlen($k2);
		});
		$resultQuery = $query;
		$paramNum = 0;
		foreach ($sqlParams as $paName => $paValue) {
			if(gettype($paValue)==="array") {
				$paramList = [];
				foreach ($paValue as $i=>$value) {
					// $paramList[] = $paName . "_param$i";
					$paramList[] = ":pdoParam$paramNum";
					$paramNum++;
				}
				$resultQuery = str_replace($paName,implode(",", $paramList),$resultQuery);
			}
		}
		return $resultQuery;
	}

    /**
     * Convert type to PdoParamType
     * 
     * @param string $type Type
     * @return intger The PDO Param Type 
     */	
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

    /**
     * Perform data binding
     * 
     * @param string $stm Query need to bind params
     * @param array $sqlParams The parameters will be bound to query
     */	
	protected function bindParams($stm, $sqlParams) {
		if (empty($sqlParams)) $sqlParams = [];
		uksort($sqlParams, function($k1, $k2) {
			return strlen($k1) < strlen($k2);
		});
		$paramNum = 0;
		foreach ($sqlParams as $paName => $paValue) {
			$type = gettype($paValue);
			if ($type === 'array') {
				foreach ($paValue as $i=>$value) {
					$paramType = $this->typeToPDOParamType(gettype($value));
					$paramName = ":pdoParam$paramNum";
					$paramNum++;
					// $stm->bindValue($paName . "_param$i", $value, $paramType);
					$stm->bindValue($paramName, $value, $paramType);
				}
			} else {
				$paramType = $this->typeToPDOParamType($type);
				$stm->bindValue($paName, $paValue, $paramType);
			}
		}
	}

	/**
	 * Guess type
	 * 
	 * @param string $native_type Native type of PDO
	 * @return string KoolReport type
	 */
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

	/**
	 * Guess type from value
	 * 
	 * @param mixed $value The value
	 * @param string The type of value
	 */
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
	
	/**
	 * Start piping data
	 */
	public function start()
	{
		$metaData = array("columns"=>array());

        if ($this->countTotal) {
            $totalQuery = $this->prepareParams($this->totalQuery,$this->sqlParams);
            $stm = $this->connection->prepare($totalQuery);
			$this->bindParams($stm,$this->sqlParams);
			$stm->execute();
			$error = $stm->errorInfo();
			if($error[2]!=null)
			{
				throw new \Exception("Query Error >> [".$error[2]."] >> $totalQuery");
				return;
			}
			$row = $stm->fetch();
			$result = $row[0];
            $metaData['totalRecords'] = $result;
        }

        if ($this->countFilter) {
            $filterQuery = $this->prepareParams($this->filterQuery,$this->sqlParams);
            $stm = $this->connection->prepare($filterQuery);
			$this->bindParams($stm,$this->sqlParams);
			$stm->execute();
			$error = $stm->errorInfo();
			if($error[2]!=null)
			{
				throw new \Exception("Query Error >> [".$error[2]."] >> $filterQuery");
				return;
			}
			$row = $stm->fetch();
			$result = $row[0];
            $metaData['filterRecords'] = $result;
		}
		$row = null;

		$query = $this->prepareParams($this->query,$this->sqlParams);
		// echo "pdodatasource start query=$query <br>";
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

		// $metaData = array("columns"=>array());
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
			// print_r($row); echo '<br>';
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
