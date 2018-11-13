<?php
/**
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 
 
 "sqlsrv"=>array(
    'datahost' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'automaker',
    'charset' => 'utf8',  
    'class' => "\koolreport\datasources\SQLSRVDataSource",
    'returnDatesAsStrings'=>true  
  ),
 
 */

namespace koolreport\datasources;
use \koolreport\core\DataSource;
use \koolreport\core\Utility as Util;

class SQLSRVDataSource extends DataSource
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
     * @var array $queryParams The params of query
     */    
    protected $queryParams;

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
        $host = Util::get($this->params,"host","");//host\instanceName
        $username = Util::get($this->params,"username","");
        $password = Util::get($this->params,"password","");
        $dbname = Util::get($this->params,"dbname","");
        $returnDatesAsStrings = Util::get($this->params,"returnDatesAsStrings",true);
        $charset = Util::get($this->params,"charset",'utf-8');
        $connectionInfo = array( "Database"=>$dbname, "UID"=>$username, "PWD"=>$password, 
            'ReturnDatesAsStrings' => $returnDatesAsStrings, 'CharacterSet' => $charset);

        $key = md5($host.$username.$password.$dbname);
        if(isset(SQLSRVDataSource::$connections[$key]))
        {
            $this->connection = SQLSRVDataSource::$connections[$key];
        }
        else
        {
            $conn = sqlsrv_connect( $host, $connectionInfo);
            if($conn) 
            {
                $this->connection = $conn;
            }
            else
            {
                throw new \Exception("Connection could not be established: ".print_r( sqlsrv_errors(), true));
            }    
            SQLSRVDataSource::$connections[$key] = $this->connection;
        }
	}
	
    /**
     * Set the query and params
     * 
     * @param string $query The SQL query statement
     * @param array $sqlParams The parameters of SQL query
     * @return PostgreSQLDataSource This datasource object
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
        $search = Util::get($queryParams, 'search', '');
        $searchSql = ! empty($search) ? "WHERE $search" : "";

        $order = Util::get($queryParams, 'order', '');
        $orderSql = ! empty($order) ? "ORDER BY $order" : "";
            
        $start = (int) Util::get($queryParams, 'start', 0);
        $length = (int) Util::get($queryParams, 'length', -1);
        $limit =  $length > -1 ? "OFFSET $start ROWS
        FETCH NEXT $length ROWS ONLY" : "";

        $filterQuery = "SELECT count(*) FROM ($query) tmp $searchSql";
        $totalQuery = "SELECT count(*) FROM ($query) tmp";
        $processedQuery = "select * from ($query) tmp $searchSql $orderSql $limit";
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
     * @return SQLSRVDataSource This datasource
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
    function map_field_type_to_bind_type($field_type) {
        switch ($field_type) {
            case -5:
            case -2:
            case -7:
            case 3:
            case 6:
            case -4:
            case 4:
            case 2:
            case 7:
            case 5:
            case -6:
                return 'number';
            case 91:
                return 'date';
            case -2:
            case 93:
                return 'datetime';
            
            case 1:
            case -10:
            case -3:
            case -9:
            case 12:
                return 'string';

            default:
                return 'unknown';
        }
    }
	
    /**
     * Start piping data
     */	
	public function start()
	{
        // echo "query=" . $this->query . '<br>';
        $metaData = array("columns"=>array());

        if ($this->countTotal) {
            $query = $this->bindParams($this->totalQuery,$this->sqlParams);
            $stmt = sqlsrv_query( $this->connection, $query);
            if( $stmt === false ) 
                die( print_r( sqlsrv_errors(), true));
            $row = sqlsrv_fetch_array($stmt);
            $total = $row[0];
            $metaData['totalRecords'] = $total;
        }

        if ($this->countFilter) {
            $query = $this->bindParams($this->filterQuery,$this->sqlParams);
            $stmt = sqlsrv_query( $this->connection, $query);
            if( $stmt === false ) 
                die( print_r( sqlsrv_errors(), true));
            $row = sqlsrv_fetch_array($stmt);
            $total = $row[0];
            $metaData['filterRecords'] = $total;
        }

        $query = $this->bindParams($this->query,$this->sqlParams);
        $stmt = sqlsrv_query( $this->connection, $query);
        if( $stmt === false ) 
            die( print_r( sqlsrv_errors(), true));

        $finfo = sqlsrv_field_metadata($stmt);
		
		$numcols = count($finfo);
		for($i=0; $i<$numcols; $i++) {
            $type = $this->map_field_type_to_bind_type($finfo[$i]['Type']);
			$metaData["columns"][$finfo[$i]['Name']] = array(
				"type"=>$type,
			);
            switch($type)
            {
                case "datetime":
                    $metaData["columns"][$finfo[$i]['Name']]["format"] = "Y-m-d H:i:s";
                    break;
                case "date":
                    $metaData["columns"][$finfo[$i]['Name']]["format"] = "Y-m-d";
                    break;
            }
        }
				
		$this->sendMeta($metaData,$this);
    
		$this->startInput(null);
		
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) )
			$this->next($row, $this);
    
		$this->endInput(null);
	}
}