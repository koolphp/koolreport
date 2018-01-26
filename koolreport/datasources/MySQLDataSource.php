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
use \koolreport\core\Utility;

class MySQLDataSource extends DataSource
{
	protected $connection;
  protected $query;
  protected $sqlParams;
	protected function onInit()
	{		
		$host = Utility::get($this->params,"host","");
		$username = Utility::get($this->params,"username","");
		$password = Utility::get($this->params,"password","");
		$dbname = Utility::get($this->params,"dbname","");
		$charset = Utility::get($this->params,"charset", null);
		
    $this->connection = new \mysqli($host, $username, $password, $dbname);
    /* check connection */
    if ($this->connection->connect_errno) {
      echo "Failed to connect to MySQL: (" . 
        $this->connection->connect_errno . ") " . 
        $this->connection->connect_error;
    }

    /* change character set */
    if (isset($charset) && ! $this->connection->set_charset($charset)) {
      printf("Error loading character set $charset: %s\n", 
        $this->connection->error);
      exit();
    }
	}
	
	public function query($query, $sqlParams=null)
	{
		$this->query =  (string)$query;
    if($sqlParams != null)
			$this->sqlParams = $sqlParams;
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
					$value = "'".implode("','",$value)."'";
					$query = str_replace($key,$value,$query);
				}
				else if(gettype($value)==="string")
				{
					$query = str_replace($key,"'$value'",$query);
				}
				else
				{
					$query = str_replace($key,$value,$query);
				}
			}
		}
		return $query;
	}
	
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
	
	public function start()
	{
    $query = $this->bindParams($this->query, $this->sqlParams);
		$result = $this->connection->query($query);
    
    $finfo = $result->fetch_fields();

		$metaData = array("columns"=>array());
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
