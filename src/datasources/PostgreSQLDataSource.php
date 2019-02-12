<?php
/**
 * This file contain PostgreSQLDataSource
 * 
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* 
 "postgresql"=>array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbbase' => 'automaker',
    'class' => "\koolreport\datasources\PostgreSQLDataSource"  
  ),
 
 */

namespace koolreport\datasources;
use \koolreport\core\DataSource;
use \koolreport\core\Utility as Util;

/**
 * PostgreSQLDataSource helps to connect to  PostgreSQL database
 * 
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class PostgreSQLDataSource extends DataSource
{
    /**
     * List of available connections for reusing
     * 
     * @var array $connections List of available connections for reusing
     */    
    static $connections;
    
    /**
     * The current connection
     * 
     * @var $connection The current connection
     */    
    protected $connection;
    
    /**
     * The query
     * 
     * @var string $query The query
     */    
    protected $query;
    
    /**
     * The params of query
     * 
     * @var array $sqlParams The params of query
     */    
    protected $sqlParams;
    
    /**
     * Whether the total should be counted.
     * 
     * @var bool $countToal Whether the total should be counted.
     */
    protected $countTotal;
    
    /**
     * Whether the filter should be counted
     * 
     * @var bool $countFilter Whether the filter should be counted
     */    
    protected $countFilter;
    
    /**
     * DataSource initiation
     * 
     * @return null
     */
    protected function onInit()
    {
        $host = Util::get($this->params, "host", "");//host\instanceName
        $username = Util::get($this->params, "username", "");
        $password = Util::get($this->params, "password", "");
        $dbname = Util::get($this->params, "dbname", "");
        $connString = "host=$host dbname=$dbname user=$username password=$password";
        
        $key = md5($connString);

        if (isset(PostgreSQLDataSource::$connections[$key])) {
            $this->connection = PostgreSQLDataSource::$connections[$key];
        } else {
            $conn = pg_connect($connString);
            if ($conn) {
                $this->connection = $conn;
            } else {
                throw new \Exception("Could not connect to database");
            }
            PostgreSQLDataSource::$connections[$key] = $this->connection;
        }
        
    }
    
    /**
     * Set the query and params
     * 
     * @param string $query     The SQL query statement
     * @param array  $sqlParams The parameters of SQL query
     * 
     * @return PostgreSQLDataSource This datasource object
     */
    public function query($query, $sqlParams=null)
    {
        $this->query =  (string)$query;
        if ($sqlParams!=null) {
            $this->sqlParams = $sqlParams;
        }
        return $this;
    }

    /**
     * Process query to additional condition
     * 
     * @param string $query       The query
     * @param array  $queryParams The parameters for query
     * 
     * @return array Information of query
     */
    static function processQuery($query, $queryParams)
    {
        $search = Util::get($queryParams, 'search', '');
        $searchSql = ! empty($search) ? "WHERE $search" : "";

        $order = Util::get($queryParams, 'order', '');
        $orderSql = ! empty($order) ? "ORDER BY $order" : "";
            
        $start = (int) Util::get($queryParams, 'start', 0);
        $length = (int) Util::get($queryParams, 'length', -1);
        $limit =  $length > -1 ? "LIMIT $length OFFSET $start" : "";

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
     * 
     * @return PostgreSQLDataSource Return itself for cascade 
     */
    public function queryProcessing($queryParams) 
    {
        list($this->query, $this->totalQuery, $this->filterQuery)
            = self::processQuery($this->query, $queryParams);

        $this->countTotal = Util::get($queryParams, 'countTotal', false);
        $this->countFilter = Util::get($queryParams, 'countFilter', false);

        return $this;
    }

    /**
     * Insert params for query
     * 
     * @param array $sqlParams The parameters for query
     * 
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
     * @param string $query     Query need to bind params
     * @param array  $sqlParams The parameters will be bound to query
     * 
     * @return string Procesed query 
     */
    protected function bindParams($query, $sqlParams)
    {
        if (empty($sqlParams)) {
            $sqlParams = [];
        } 
        uksort(
            $sqlParams,
            function ($k1, $k2) {
                return strlen($k1) < strlen($k2);
            }
        );
        foreach ($sqlParams as $key=>$value) {
            if (gettype($value)==="array") {
                $value = array_map(
                    function ($v) {
                        return $this->escape($v);
                    },
                    $value
                );
                $value = "(".implode(",", $value).")";
                $query = str_replace($key, $value, $query);
            } else {
                $query = str_replace($key, $this->escape($value), $query);
            }
        }
        return $query;
    }
    
    /**
     * Escape value for SQL safe
     * 
     * @param string $str The string need to be escape
     * 
     * @return string Escaped string
     */    
    protected function escape($str)
    {
        if (is_string($str) || (is_object($str) && method_exists($str, '__toString'))) {
            return "'".$this->escapeStr($str)."'";
        } elseif (is_bool($str)) {
            return ($str === false) ? 0 : 1;
        } elseif ($str === null) {
            return 'NULL';
        }
        return $str;
    }

    /**
     * Escape string
     * 
     * @param string $str The string needed to be escaped.
     * 
     * @return string The escaped string
     */
    protected function escapeStr($str)
    {
        return pg_escape_string($str);
    }
    
    /**
     * Map field type to bind type
     * 
     * @param strng $native_type The type of field
     * 
     * @return string KoolReport type of field
     */
    protected function mapFieldTypeToBindType($native_type)
    {
        $pg_to_php = array(
            'bit' => 'number',
            'boolean' => 'string',
            'box' => 'string',
            'character' => 'string',
            'char' => 'number',
            'bytea' => 'number',
            'cidr' => 'string',
            'circle' => 'string',
            'date' => 'datetime',
            'daterange' => 'datetime',
            'real' => 'number',
            'double precision' => 'number',
            'inet' => 'number',
            'smallint' => 'number',
            'smallserial' => 'number',
            'integer' => 'number',
            'serial' => 'number',
            'int4range' => 'number',
            'bigint' => 'number',
            'bigserial' => 'number',
            'int8range' => 'number',
            'interval' => 'number',
            'json' => 'string',
            'lseg' => 'string',
            'macaddr' => 'string',
            'money' => 'number',
            'decimal' => 'number',
            'numeric' => 'number',
            'numrange' => 'number',
            'path' => 'string',
            'point' => 'string',
            'polygon' => 'string',
            'text' => 'string',
            'time' => 'datetime',
            'time without time zone' => 'datetime',
            'timestamp' => 'datetime',
            'timestamp without time zone' => 'datetime',
            'timestamp with time zone' => 'datetime',
            'time with time zone' => 'datetime',
            'tsquery' => 'string',
            'tsrange' => 'string',
            'tstzrange' => 'string',
            'tsvector' => 'string',
            'uuid' => 'number',
            'bit varying' => 'number',
            'character varying' => 'string',
            'varchar' => 'string',
            'xml' => 'string'
        );
        
        $native_type = strtolower($native_type);
        
        foreach ($pg_to_php as $key=>$value) {
            if (strpos($native_type, $key)!==false) {
                return $value;
            }
        }
        return "unknown";
    }
    
    /**
     * Start piping data
     * 
     * @return null
     */
    public function start()
    {
        $metaData = array("columns"=>array());

        if ($this->countTotal) {
            $totalQuery = $this->bindParams($this->totalQuery, $this->sqlParams);
            $totalResult = pg_query($this->connection, $totalQuery);
            if (!$totalResult) {
                echo pg_last_error($this->connection);
                exit;
            }
            $row = pg_fetch_array($totalResult);
            $total = $row[0];
            $metaData['totalRecords'] = $total;
        }

        if ($this->countFilter) {
            $filterQuery = $this->bindParams($this->filterQuery, $this->sqlParams);
            $filterResult = pg_query($this->connection, $filterQuery);
            if (!$filterResult) {
                echo pg_last_error($this->connection);
                exit;
            }
            $row = pg_fetch_array($filterResult);
            $total = $row[0];
            $metaData['filterRecords'] = $total;
        }

        $query = $this->bindParams($this->query, $this->sqlParams);
        $result = pg_query($this->connection, $query);
        if (! $result) {
            echo pg_last_error($this->connection);
            exit;
        }

        $num_fields = pg_num_fields($result);

        for ($i=0; $i<$num_fields; $i++) {
            $name = pg_field_name($result, $i);
            $type = pg_field_type($result, $i);
            $type = $this->mapFieldTypeToBindType($type);
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
                
        $this->sendMeta($metaData, $this);
    
        $this->startInput(null);
        
        while ($row = pg_fetch_assoc($result)) {
            $this->next($row, $this);
        }

        $this->endInput(null);
    }
}
