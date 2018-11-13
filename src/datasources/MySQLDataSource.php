<?php
/**
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 
 
 "mysql"=>array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname' => 'automaker',
    'charset' => 'utf8',  
    'class' => "\koolreport\datasources\MySQLDataSource"  
  ),
 
 */

namespace koolreport\datasources;
use \koolreport\core\DataSource;
use \koolreport\core\Utility as Util;

class MySQLDataSource extends DataSource
{
    /**
     * @var $connections Contains list of reuable connection
     */
    static $connections;

    /**
     * @var $connection Current data connection
     */
    protected $connection;
    
    /**
     * @var string $query The SQL query
     */
    protected $query;

    /**
     * @var array $sqlParams The parameters for SQL query
     */
    protected $sqlParams;

    /**
     * @var bool $countTotal Whether total should be count
     */
    protected $countTotal = false;

    /**
     * @var bool $countFilter Whether filter should be count
     */
    protected $countFilter = false;

    /**
     * Init MySQLdataSource
     */
	protected function onInit()
	{		
		$host = Util::get($this->params,"host","");
		$username = Util::get($this->params,"username","");
		$password = Util::get($this->params,"password","");
		$dbname = Util::get($this->params,"dbname","");
		$charset = Util::get($this->params,"charset", null);
        
        $key = md5($host.$username.$password.$dbname);
        if(isset(MySQLDataSource::$connections[$key]))
        {
            $this->connection = MySQLDataSource::$connections[$key];
        }
        else
        {
            $this->connection = new \mysqli($host, $username, $password, $dbname);
            /* check connection */
            if ($this->connection->connect_errno) {
                throw new \Exception("Failed to connect to MySQL: (" . 
                    $this->connection->connect_errno . ") " . 
                    $this->connection->connect_error);
            }
            MySQLDataSource::$connections[$key] = $this->connection;    
        }

        /* change character set */
        if (isset($charset) && ! $this->connection->set_charset($charset)) {
            throw new \Exception("Error loading character set $charset: ".$this->connection->error);
        }
	}
    
    /**
     * Set the query and parameters
     * 
     * @param string $query The query statement
     * @param array $sqlParams The parameters for query
     */
	public function query($query, $sqlParams=null)
	{
		$this->query = (string)$query;
        if($sqlParams != null)
			$this->sqlParams = $sqlParams;
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
        $limit =  $length > -1 ? "LIMIT $start, $length" : "";

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
     * @return MySQLDataSource This datasource
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
        return $this->connection->real_escape_string($str);
    }
    
    /**
     * Map field type to bind type
     * 
     * @param strng $field_type The type of field
     * @return string KoolReport type of field
     */
    function map_field_type_to_bind_type($field_type) {
        switch ($field_type) {
        case MYSQLI_TYPE_DECIMAL:
        case MYSQLI_TYPE_NEWDECIMAL:
        case MYSQLI_TYPE_FLOAT:
        case MYSQLI_TYPE_DOUBLE:
        case MYSQLI_TYPE_BIT:
        case MYSQLI_TYPE_TINY:
        case MYSQLI_TYPE_SHORT:
        case MYSQLI_TYPE_LONG:
        case MYSQLI_TYPE_LONGLONG:
        case MYSQLI_TYPE_INT24:
        case MYSQLI_TYPE_YEAR:
        case MYSQLI_TYPE_ENUM:
            return 'number';

        case MYSQLI_TYPE_DATE:
            return 'date';

        case MYSQLI_TYPE_TIME:
            return 'time';
        case MYSQLI_TYPE_TIMESTAMP:
        case MYSQLI_TYPE_DATETIME:
        case MYSQLI_TYPE_NEWDATE:
            return 'datetime';
        
        case MYSQLI_TYPE_VAR_STRING:
        case MYSQLI_TYPE_STRING:
        case MYSQLI_TYPE_CHAR:
        case MYSQLI_TYPE_GEOMETRY:
        case MYSQLI_TYPE_TINY_BLOB:
        case MYSQLI_TYPE_MEDIUM_BLOB:
        case MYSQLI_TYPE_LONG_BLOB:
        case MYSQLI_TYPE_BLOB:
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
        $metaData = array("columns"=>array());

        if ($this->countTotal) {
            $totalQuery = $this->bindParams($this->totalQuery, $this->sqlParams);
            $totalResult = $this->connection->query($totalQuery);
            if($totalResult===FALSE)
                throw new \Exception("Error on query >>> ".$this->connection->error);
            $row = $totalResult->fetch_array();
            $result = $row[0];
            $metaData['totalRecords'] = $result;
        }

        if ($this->countFilter) {
            $filterQuery = $this->bindParams($this->filterQuery, $this->sqlParams);
            $filterResult = $this->connection->query($filterQuery);
            if($filterResult===FALSE)
                throw new \Exception("Error on query >>> ".$this->connection->error);
            $row = $filterResult->fetch_array();
            $result = $row[0];
            $metaData['filterRecords'] = $result;
        }

        $query = $this->bindParams($this->query, $this->sqlParams);
        $result = $this->connection->query($query);
        
        if($result===FALSE)
        {
            throw new \Exception("Error on query >>> ".$this->connection->error);
        }

        $finfo = $result->fetch_fields();

        
        $numcols = count($finfo);
        for($i=0; $i<$numcols; $i++) 
        {
            $type = $this->map_field_type_to_bind_type($finfo[$i]->type);
                $metaData["columns"][$finfo[$i]->name] = array(
                        "type"=>$type,
                    );
            switch($type)
            {
                case "datetime":
                    $metaData["columns"][$finfo[$i]->name]["format"] = "Y-m-d H:i:s";
                break;
                case "date":
                    $metaData["columns"][$finfo[$i]->name]["format"] = "Y-m-d";
                break;
                case "time":
                    $metaData["columns"][$finfo[$i]->name]["format"] = "H:i:s";
                break;          
            }
        }

		$this->sendMeta($metaData,$this);
    
		$this->startInput(null);
		
		while ($row = $result->fetch_assoc())
			$this->next($row, $this);
    
		$this->endInput(null);
	}
}
