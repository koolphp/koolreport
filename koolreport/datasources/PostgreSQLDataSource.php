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
    'dbbase' => 'automaker',
    'class' => "\koolreport\datasources\PostgreSQLDataSource"  
  ),
 
 */

namespace koolreport\datasources;
use \koolreport\core\DataSource;
use \koolreport\core\Utility;

class PostgreSQLDataSource extends DataSource
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
        $connString = "host=$host dbname=$dbname user=$username password=$password";
        
        $key = md5($connString);

        if(isset(PostgreSQLDataSource::$connections[$key]))
        {
            $this->connection = PostgreSQLDataSource::$connections[$key];
        }
        else
        {
            $conn = pg_connect($connString);
            if( $conn ) 
            {
                $this->connection = $conn;
            }
            else
            {
                throw new \Exception("Could not connect to database");
            }
            PostgreSQLDataSource::$connections[$key] = $this->connection;
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
        return pg_escape_string($str);
    }
	
    protected function map_field_type_to_bind_type($native_type)
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
		
		foreach($pg_to_php as $key=>$value)
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
        $result = pg_query($this->connection, $query);
        if (! $result) {
            echo pg_last_error($this->connection);
            exit;
        }

        $num_fields = pg_num_fields($result);

		$metaData = array("columns"=>array());
        
		for($i=0; $i<$num_fields; $i++) 
        {
            $name = pg_field_name($result, $i);
            $type = pg_field_type($result, $i);
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
		
		while($row = pg_fetch_assoc($result))
			$this->next($row, $this);
    
		$this->endInput(null);
	}
}
