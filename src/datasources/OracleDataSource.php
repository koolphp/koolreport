<?php
/**
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 
    How to install Oracle driver for Apache on Windows:
    - Install Oracle database 32 bit only (php on Windows is only 32 bit).
    - Download and extract Oracle Instant Client 32 bit, add the extracted folder 
    to Windows' Path environment variable.
    - Install the correct Microsoft Visual Studio Redistributable version.
    For example, VC 2013 for Instant Client 12.2.0.1.0
    - Copy all dll files from Oracle Instant Client 32 bit to Apache's bin folder.
    - Enable extension=php_oci8_12c.dll in php.ini.
    - Restart Apache.

    For Pdo with Oracle for Apache on Windows:
    - Install Oracle database 32 bit only (php on Windows is only 32 bit).
    - Download and extract Oracle Instant Client 32 bit, add the extracted folder 
    to Windows' Path environment variable.
    - Enable extension=php_pdo_oci.dll in php.ini.
    - Restart Apache.

    "oracle"=>array(
        'connectionString' => 'localhost:1521/XE',
        'username' => 'sa',
        'password' => 'root',
        'class' => "\koolreport\datasources\OracleDataSource",
    ),

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

class OracleDataSource extends DataSource
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
    protected $countTotal = false;

    /**
     * @var bool $countFilter Whether the filter should be counted
     */
    protected $countFilter = false;

	protected function onInit()
	{		
        $username = Util::get($this->params,"username","");
        $password = Util::get($this->params,"password","");
        $connString = Util::get($this->params,"connectionString","");

        $key = md5($username.$password.$connString);

        if(isset(OracleDataSource::$connections[$key]))
        {
            $this->connection = OracleDataSource::$connections[$key];
        }
        else
        {
            $conn = oci_connect($username, $password, $connString);
            if( $conn ) 
                $this->connection = $conn;
            else{
                throw new \Exception("Connection failed: " . print_r(oci_error(),true));
            }
            OracleDataSource::$connections[$key] = $this->connection;
        }
	}
    
    /**
     * Set the query and params
     * 
     * @param string $query The SQL query statement
     * @param array $sqlParams The parameters of SQL query
     * @return OracleDataSource This datasource object
     */
	public function query($query, $sqlParams=null)
	{
		$this->query =  (string)$query;
        if($sqlParams!=null)
		{
			$this->sqlParams = $sqlParams;
		}
		return $this;
    }

    /**
     * Process query to additional condition
     */
    static function processQuery($query, $queryParams) {
        $search = Util::get($queryParams, 'search', '1=1');
        $searchSql = "WHERE $search";

        $order = Util::get($queryParams, 'order', '');
        $orderSql = ! empty($order) ? "ORDER BY $order" : "";
            
        $start = (int) Util::get($queryParams, 'start', 0) + 1;
        $length = (int) Util::get($queryParams, 'length', -1);
        $end = $start + $length;
        $limitSearchSql = $searchSql . ($length > -1 ? " AND rownum < $end" : "");
        // Oracle version >= 12
        // $limit =  $length > -1 ? 
        //     "OFFSET $start ROWS FETCH NEXT $length ROWS ONLY" : ""; 

        $filterQuery = "SELECT count(*) FROM ($query) tmp $searchSql";
        $totalQuery = "SELECT count(*) FROM ($query) tmp";
        $processedQuery = "select * from (select a.*, rownum as rnum 
            from (select * from ($query) $orderSql) a $limitSearchSql ) tmp where rnum >= $start";
        // echo "query=" . $processedQuery . '<br>';
        return [$processedQuery, $totalQuery, $filterQuery];
    }

    /**
     * Transform query
     * 
     * @param array $queryParams Parameters of query 
     */    
    public function queryProcessing($queryParams) 
    {
        list($this->query, $this->totalQuery, $this->filterQuery) =
            self::processQuery($this->query, $queryParams);

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
     * Perform data binding
     * 
     * @param string $query Query need to bind params
     * @param array $sqlParams The parameters will be bound to query
     * @return string Procesed query 
     */	
    protected function bindParams($query, $sqlParams)
	{
		if (empty($sqlParams)) $sqlParams = [];
		uksort($sqlParams, function($k1, $k2) {
			return strlen($k1) < strlen($k2);
		});
        foreach($sqlParams as $key=>$value)
        {
            if(gettype($value)==="array")
            {
                $value = array_map(function($v){
                    return $this->escape($v);
                },$value);
                $value = "(".implode(",",$value).")";
                $query = str_replace($key,$value,$query);
            }
            else
            {
                $query = str_replace($key,$this->escape($value),$query);
            }
        }
		return $query;
    }
    
    /**
     * Escape value for SQL safe
     * 
     * @param string $string The string need to be escape
     */    
    protected function escape($str)
    {
		if (is_string($str) OR (is_object($str) && method_exists($str, '__toString')))
		{
			return "'".$this->escape_str($str)."'";
		}
		elseif (is_bool($str))
		{
			return ($str === FALSE) ? 0 : 1;
		}
		elseif ($str === NULL)
		{
			return 'NULL';
		}

		return $str;
    }

    /**
     * Escape string
     * 
     * @param string $str The string needed to be escaped.
     * @return string The escaped string
     */
    protected function escape_str($str)
    {
        return str_replace("'","''",$str);
    }

    /**
     * Map field type to bind type
     * 
     * @param strng $field_type The type of field
     * @return string KoolReport type of field
     */	
    protected function map_field_type_to_bind_type($native_type)
	{
		$oracleDatatypeMap = array(
            'varchar2' => 'string',
            'nvarchar2' => 'string',
            'number' => 'number',
            'long' => 'number',
            'date' => 'datetime',
            'binary_float' => 'number',
            'binary_double' => 'number',
            'timestamp' => 'datetime',
            'interval year' => 'datetime',
            'interval day' => 'datetime',
            'raw' => 'string',
            'long raw' => 'string',
            'rowid' => 'string',
            'urowid' => 'string',
            'char' => 'string',
            'nchar' => 'string',
            'clob' => 'string',
            'nclob' => 'string',
            'blob' => 'string',
            'bfile' => 'string',
        );
		
		$native_type = strtolower($native_type);
        if (isset($oracleDatatypeMap[$native_type]))
            return $oracleDatatypeMap[$native_type];
        else
            return "unknown";
    }
    
    /**
     * Start piping data
     */	
	public function start()
	{
        $metaData = array("columns"=>array());

        if ($this->countTotal) {
            $totalQuery = $this->bindParams($this->totalQuery, $this->sqlParams);
            $totalResult = oci_parse($this->connection, $totalQuery);
            if(! $totalResult) {
                echo oci_error();
                exit;
            }
            oci_execute($totalResult);
            $row = oci_fetch_array($totalResult, OCI_BOTH+OCI_RETURN_NULLS);
            $total = $row[0];
            $metaData['totalRecords'] = $total;
        }

        if ($this->countFilter) {
            $filterQuery = $this->bindParams($this->filterQuery, $this->sqlParams);
            $filterResult = oci_parse($this->connection, $filterQuery);
            if(! $filterResult){
                echo oci_error();
                exit;
            }
            oci_execute($filterResult);
            $row = oci_fetch_array($filterResult, OCI_BOTH+OCI_RETURN_NULLS);
            $total = $row[0];
            $metaData['filterRecords'] = $total;
        }

        $query = $this->bindParams($this->query,$this->sqlParams);
        $stid = oci_parse($this->connection, $query);
        if (! $stid) {
            echo oci_error();
            exit;
        }
        oci_execute($stid);
        $num_fields = oci_num_fields($stid);
		// $metaData = array("columns"=>array());
		for($i=0; $i<$num_fields; $i++) {
            $name = oci_field_name($stid, $i+1);
            $type = oci_field_type($stid, $i+1);
            $type = $this->map_field_type_to_bind_type($type);
			$metaData["columns"][$name] = array(
				"type"=>$type,
			);
            switch($type)
            {
                case "datetime":
                    $metaData["columns"][$name]["format"] = "Y-m-d H:i:s";
                    break;
                case "date":
                    $metaData["columns"][$name]["format"] = "Y-m-d";
                    break;
            }
        }
				
		$this->sendMeta($metaData,$this);
    
		$this->startInput(null);
		
		while($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS))
			$this->next($row, $this);
    
		$this->endInput(null);
	}
}
