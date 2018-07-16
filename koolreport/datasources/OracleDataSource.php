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
use \koolreport\core\Utility;

class OracleDataSource extends DataSource
{
    static $connections;
	protected $connection;
    protected $query;
    protected $sqlParams;
	protected function onInit()
	{		
        $username = Utility::get($this->params,"username","");
        $password = Utility::get($this->params,"password","");
        $connString = Utility::get($this->params,"connectionString","");

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
	
	public function start()
	{
        $query = $this->bindParams($this->query,$this->sqlParams);
        $stid = oci_parse($this->connection, $query);
        if (! $stid) {
            echo oci_error();
            exit;
        }
        oci_execute($stid);
        $num_fields = oci_num_fields($stid);
		$metaData = array("columns"=>array());
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
