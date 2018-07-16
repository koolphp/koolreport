<?php
/**
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 
 
 "mysql"=>array(
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
use \koolreport\core\Utility;

class SQLSRVDataSource extends DataSource
{
    static $connections;
	protected $connection;
    protected $query;
    protected $sqlParams;
	protected function onInit()
	{		
        $host = Utility::get($this->params,"host","");//host\instanceName
        $username = Utility::get($this->params,"username","");
        $password = Utility::get($this->params,"password","");
        $dbname = Utility::get($this->params,"dbname","");
        $returnDatesAsStrings = Utility::get($this->params,"returnDatesAsStrings",true);
        $charset = Utility::get($this->params,"charset",'utf-8');
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
	
	public function query($query, $sqlParams=null)
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
	
    protected function bindParams($query, $sqlParams)
	{
		if($sqlParams!=null)
		{
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
		}
		return $query;
	}
    
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

    protected function escape_str($str)
    {
        return str_replace("'","''",$str);
    }
	
    function map_field_type_to_bind_type($field_type) {
        switch ($field_type) {
        case SQL_BIGINT:
        case SQL_BINARY:
        case SQL_BIT:
        case SQL_DECIMAL:
        case SQL_FLOAT:
        case SQL_LONGVARBINARY:
        case SQL_INTEGER:
        case SQL_NUMERIC:
        case SQL_REAL:
        case SQL_SMALLINT:
        case SQL_TINYINT:
        case MYSQLI_TYPE_ENUM:
            return 'number';
        case SQL_DATE:
            return 'date';
        case SQL_TIMESTAMP:
        case SQL_TIMESTAMP:
            return 'datetime';
        
        case SQL_CHAR:
        case SQL_LONGVARCHAR:
        case SQL_VARBINARY:
        case SQL_VARCHAR:
            return 'string';

        default:
            return 'unknown';
        }
    }
	
	public function start()
	{
        $query = $this->bindParams($this->query,$this->sqlParams);
        $stmt = sqlsrv_query( $this->connection, $query);
        if( $stmt === false ) 
            die( print_r( sqlsrv_errors(), true));

        $finfo = sqlsrv_field_metadata($stmt);
		$metaData = array("columns"=>array());
		$numcols = count($finfo);
		for($i=0; $i<$numcols; $i++) 
        {
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
