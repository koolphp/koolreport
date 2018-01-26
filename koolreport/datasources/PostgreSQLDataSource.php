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
    'class' => "\koolreport\datasources\MySQLDataSource"  
  ),
 
 */

namespace koolreport\datasources;
use \koolreport\core\DataSource;
use \koolreport\core\Utility;

class PostgreSQLDataSource extends DataSource
{
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
        $conn = pg_connect($connString);

        if( $conn ) 
        $this->connection = $conn;
        else{
        die("Connection failed.");
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
				else
				{
					$query = str_replace($key,"'$value'",$query);
				}
			}
		}
		return $query;
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
