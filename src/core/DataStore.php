<?php
/**
 * This file contains foundation class for datastore
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\core;
use IteratorAggregate;
use ArrayIterator;
use ArrayAccess;

class DataStore extends Node implements IteratorAggregate, ArrayAccess
{
	/**
	 * @var array $rows Contain array of associate array containing data of datastore
	 */
	protected $rows;
	/**
	 * @var integer $index Index of current row use by pop() function
	 */
	protected $index=-1;
	
	public function __construct($rows = null,$meta = null)
	{
		parent::__construct();
		$this->rows = array();
		$this->meta(array("columns"=>array()));
		if($rows!=null)
		{
			$this->data($rows);
			if($meta!=null)
			{
				$this->meta($meta);
			}
			else if($this->count()>0)
			{
				// Try to guess
				foreach($this->first() as $cName=>$cValue)
				{
					$this->metaData["columns"][$cName] = array("type"=>Utility::guessType($cValue));
				}	
			}
		}
		$this->onInit();
	}
	/**
	 * This method will be called during the initiation of DataStore
	 */
	protected function onInit()
	{
		
	}
	/**
	 * This method will be called when previous node push row of data through input() function
	 * 
	 * @since 1.0.0
	 * 
	 * @param array $row Associated array containing data of a data row
	 */
	protected function onInput($row)
	{
		$this->append($row);
	}
	
	/**
	 * Return the number of rows
	 * 
	 * @since 1.0.0
	 * 
	 * @return integer Number of data rows
	 */
    public function countData()
    {
        return count($this->rows);
    }	
	
	/**
	 * Get or set the meta data of datastore
	 * 
	 * If there is no parameter, the method will return current meta data.
	 * If metaData is input, it will save that new meta data
	 * 
	 * @since 1.0.0
	 * 
	 * @param array $metaData Associate array contaning meta data
	 * @return array Meta data in array form
	 */
	public function meta($metaData=null)
	{
		if($metaData)
		{
			$this->metaData = $metaData;
			return $this;
		}
		else
		{
			return $this->metaData;
		}
	}

	/**
	 * Get or set data inside DataStore
	 * 
	 * If no parameter is input, the method will return all rows.
	 * If rows parameter is input, method will save it
	 * 
	 * @since 1.0.0
	 * 
	 * @param array $rows Rows of data
	 * @return array Rows of data
	 */
	public function data($rows=null)
	{
		if($rows!==null)
		{
			$this->rows = $rows;
			return $this;
		}
		else
		{
			return $this->rows;
		}
	}

	/**
	 * Reset the index ready to be popped
	 *
	 */
    public function popStart()
    {
        // Start poping data, reset the index  to -1
        $this->index = -1;
		return $this;
	}
	/**
	 * Return current index
	 * 
	 * @return integer Current index
	 */
    public function getPopIndex()
    {
        return $this->index;
    }
	
	/**
	 * Return current row of index and increase index
	 * 
	 * @return array Current row of index
	 */
    public function pop()
    {
        $this->index++;
        return Utility::get($this->rows,$this->index);
	}

	/**
	 * Return a data row at index or single value at column name
	 * 
	 * @param integer $index At what index you want to get row
	 * @param string $colName What field you want to get value from
	 * 
	 * @return mixed Could be array of row or single value
	 */
	public function get($index=0,$colName=null)
	{
		if(isset($this->rows[$index]))
		{
			if($colName!==null)
			{
				if(isset($this->rows[$index][$colName]))
				{
					return $this->rows[$index][$colName];
				}
			}
			else
			{
				return $this->rows[$index];
			}
		}
		return null;
	}

	public function breakGroup($key,$func)
	{
		$data = array();
		$start = 0;
		foreach($this->rows as $i=>$row)
		{
			if(!isset($oldValue))
			{
				$oldValue = $row[$key];
			}

			if($row[$key]==$oldValue)
			{
				$oldValue = $row[$key];
				array_push($data,$row);
			}
			else
			{
				$func(new DataStore($data,$this->metaData),$start);
				$start = $i;
				$data = array($row);
				$oldValue = $row[$key];	
			}
		}
		$func(new DataStore($data,$this->metaData),$start);
	}

	/**
	 * Filter the data set with condition
	 * 
	 * Examples:
	 * 
	 * $dataStore->filter(array('age','>',45));
	 * $dataStore->filter(array('age','between',45,65));
	 * 
	 * @param array Condition to be filter contains 3 values "columName" "operator" and "value"
	 */
	public function filter()
	{
		$condition = func_get_args();
		$cName = Utility::get($condition,0);
		if(gettype($cName)=="object" && is_callable($cName))
		{
			//Able to filter by function
			return $this->filterByFunc($cName);
		}
		$operator = Utility::get($condition,1);
		$value = Utility::get($condition,2);
		$optional_value = Utility::get($condition,3);

		if($cName===null||$operator===null)
		{
			throw new \Exception('dataStore->filter() requires condition in array form ($colname,$operator,$value)');
		}

		$result = array();
		$cType = $this->metaData["columns"][$cName]["type"];
		$dtFormat = null;
		if(in_array($cType,array("datetime","date","time")))
		{
			switch($cType)
			{
				case "datetime":
				$dtFormat = Utility::get($this->metaData["columns"][$cName],"format","Y-m-d H:i:s");
				break;
				case "date":
				$dtFormat = Utility::get($this->metaData["columns"][$cName],"format","Y-m-d");
				break;
				case "time":
				$dtFormat = Utility::get($this->metaData["columns"][$cName],"format","H:i:s");
				break;
			}
			$value =  \DateTime::createFromFormat($dtFormat,$value);
		}
		
		foreach($this->rows as $row)
		{
			$columnValue = $row[$cName]; 
			if($dtFormat!==null)
			{
				$columnValue =  \DateTime::createFromFormat($dtFormat,$columnValue);
			}
			//echo $columnValue->format("Y-m-d")."<br/>";
			switch($operator)
			{
				case "=":
				case "==":
				case "equal":
					if($columnValue==$value) array_push($result,$row);
				break;
				case "===":
					if($columnValue===$value) array_push($result,$row);
				break;
				case "!=":
				case "notEqual":
					if($columnValue!=$value) array_push($result,$row);
				break;
				case "!==":
					if($columnValue!==$value) array_push($result,$row);
				break;
				case ">":
				case "gt":
					if($columnValue>$value) array_push($result,$row);
				break;
				case ">=":
					if($columnValue>=$value) array_push($result,$row);
				break;
				case "<":
				case "lt":
					if($columnValue<$value) array_push($result,$row);
				break;
				case "<=":
					if($columnValue<=$value) array_push($result,$row);
				break;
				case "contain":
				case "contains":
					if(strpos(strtolower($columnValue),strtolower($value))!==false) array_push($result,$row);
				break;	
				case "notContain":
				case "notContains":
					if(strpos(strtolower($columnValue),strtolower($value))===false) array_push($result,$row);
				break;
				case "between":
					if($value<$columnValue && $columnValue<$optional_value) array_push($result,$row);
				break;
				case "notBetween":
					if (!($value<$columnValue && $columnValue<$optional_value)) array_push($result,$row);
				break;			
				case "in":
					if(!is_array($value)) $value = array($value);
					if(in_array($columnValue,$value)) array_push($result,$row);	
				break;
				case "notIn":
					if(!is_array($value)) $value = array($value);
					if(!in_array($columnValue,$value)) array_push($result,$row);	
				break;
				case "startWith":
				case "startsWith":
					if(strpos(strtolower($columnValue), strtolower($value)) === 0) array_push($result,$row);
				break;
				case "notStartWith":
				case "notStartsWith":
					echo $columnValue;
					if(strpos(strtolower($columnValue), strtolower($value)) !== 0) array_push($result,$row);
				break;
				case "endWith":
				case "endsWith":
					if(strpos(strrev(strtolower($columnValue)), strrev(strtolower($value))) === 0) array_push($result,$row);
				break;
				case "notEndWith":
				case "notEndsWith":
					if(strpos(strrev(strtolower($columnValue)), strrev(strtolower($value))) !== 0) array_push($result,$row);
				break;
				default:
					throw new \Exception("Unknown operator [$operator]");
					return $this;
				break;
			}
		}
		return new DataStore($result,$this->metaData);
	}

	/**
	 * Return the page specified by pageSize and pageIndex
	 * 
	 * The data set will be paginated by pageSize and method will return set of
	 * data at pageIndex
	 * 
	 * @param integer $pageSize How many rows in the page
	 * @param integer $pageIndex At what page number you want to get
	 * 
	 * @return DataStore The new datastore containing rows
	 */
	public function paging($pageSize,$pageIndex)
	{
		return $this->top($pageSize,$pageIndex*$pageSize);
	}

	/**
	 * Return top number of rows
	 * 
	 * It will return top rows starting at specified offset.
	 * If offset is not specified, the default value is 0.
	 * 
	 * @param integer $num Number of top row you want to get
	 * @param integer $offset At what index you want to start getting rows
	 * 
	 * @return DataStore New DataStore containing result
	 */
	public function top($num,$offset=0)
	{
		$count = $this->countData();
		$result = array();
		for($i=$offset;$i<$num+$offset && $i<$count;$i++)
		{
			array_push($result,$this->rows[$i]);
		}
		return new DataStore($result,$this->metaData);
	}

	/**
	 * Return top percent of row
	 * 
	 * @param float $num What top percent of row you want to return
	 * 
	 * @return DataStore New datastore containing result.
	 */
	public function topByPercent($num)
	{
		$count = $this->countData();
		return $this->top(round($num*$count/100));
	}

	/**
	 * Return bottom rows of dataset
	 * 
	 * @param float $num Number of rows you want to return
	 * 
	 * @return DataStore New datastore containing result.
	 */
	public function bottom($num)
	{
		$count = $this->countData();
		$result = array();
		$start = ($count>$num)?$count-$num:0;
		for($i=$start;$i<$count;$i++)
		{
			array_push($result,$this->rows[$i]);
		}
		return new DataStore($result,$this->metaData);
	}
	/**
	 * Return the bottom rows by percent
	 * 
	 * Example: Return 20% bottom rows
	 * $dataSore->bottomByPercent(20);
	 * 
	 * @param float $num The number in percent
	 * @return DataStore The new datastore containing result 
	 */
	public function bottomByPercent($num)
	{
		$count = $this->countData();
		return $this->bottom(round($num*$count/100));
	}


	/**
	 * Return the sum of a field
	 * 
	 * Examples: $totalSales = $dataStore->sum("saleAmount");
	 * 
	 * @param string $colName Name of column you want to sum
	 * @return float Sum of column
	 */
	public function sum($colName)
	{
		$sum = 0;
		foreach($this->rows as $row)
		{
			$sum+=$row[$colName];
		}
		return $sum;
	}

	/**
	 * Return the min value of a field
	 * 
	 * Examples: $minSale = $dataStore->min("saleAmount");
	 * 
	 * @param string $colName Name of column you want to get min
	 * @return float Min value of column
	 */
	public function min($colName)
	{
		
		$min = INF;
		foreach($this->rows as $row)
		{
			if($min>$row[$colName])
			{
				$min = $row[$colName];
			}
		}
		return $min;
	}
	/**
	 * Return the max of a field
	 * 
	 * Examples: $maxSale = $dataStore->max("saleAmount");
	 * 
	 * @param string $colName Name of column you want to get max
	 * @return float Max value of column
	 */
	public function max($colName)
	{
		$max = -INF;
		foreach($this->rows as $row)
		{
			if($max<$row[$colName])
			{
				$max = $row[$colName];
			}
		}
		return $max;		
	}

	/**
	 * Return the average value of a field
	 * 
	 * Examples: $averageSale = $dataStore->avg("saleAmount");
	 * 
	 * @param string $colName Name of column you want to get average
	 * @return float Average value of column
	 */	
	public function avg($colName)
	{
		if($this->countData()>0)
		{
			return $this->sum($colName)/$this->countData();
		}
		return false;
	}

	/**
	 * Pipe rows of data to process and get result
	 * 
	 * Sometime you may need to further process data event they has reach
	 * datastore, you may do so with this method.
	 * 
	 * Examples: $groupData = $dataStore->process(Group::process(["by"=>"date"]));
	 * 
	 * @param string $colName Name of column you want to sum
	 * @return DataStore New datastore containing result
	 */
	public function process($process)
	{
		$ds = new DataStore;
		$process->pipe($ds);
		$top_process = $process;
		while($top_process->previous()!=null)
		{
			$top_process = $top_process->previous();
		}
		$top_process->receiveMeta($this->metaData,$this);
		$top_process->startInput($this);

		foreach($this->rows as $row)
		{
			$top_process->input($row,$this);
		}
		$top_process->endInput($this);
		return $ds;
	}

	/**
	 * Append a new row to the end of data set
	 * 
	 * @param array $row A row to be appended
	 * @return DataStore This datastore
	 */
	public function append($row)
	{
		array_push($this->rows,$row);
		return $this;
	}
	/**
	 * Append a new row to the end of dataset
	 * 
	 * @param array $row A row to be appended
	 * @return DataStore This datastore
	 */
	public function push($row)
	{
		return $this->append($row);	
	}

	/**
	 * Attach a row to the top of dataset
	 * 
	 * @param array $row A row to be prepended
	 * @return DataStore This datastore
	 */
	public function prepend($row)
	{
		if($row)
		{
			array_unshift($this->rows,$row);
		}
		return $this;
	}

	/**
	 * Return the number of rows in dataset
	 * 
	 * @return integer Number of rows in dataset
	 */
	public function count()
	{
		return count($this->rows);
	}

	/**
	 * Get the json representation of data set
	 * 
	 * @return string Json string representing data
	 */
	public function toJson()
	{
		//Return data as json
		return json_encode($this->rows);
	}

	/**
	 * Get all rows in array
	 * 
	 * @return array All rows of data in array
	 */
	public function toArray()
	{
		return $this->rows;
	}

	/**
	 * Get whether datastore is empty
	 * 
	 * @return boolean True if the datastore is empty
	 */
	public function isEmpty()
	{
		return $this->count()==0;
	}


	/**
	 * Get whether datastore is not empty
	 * 
	 * @return boolean True if the datastore is not empty
	 */
	public function isNotEmpty()
	{
		return $this->count()>0;
	}

	/**
	 * Loop through each rows of data set
	 * 
	 * Examples:
	 * 
	 * $store->each(function($row,$index){
	 * 		//Do something
	 * });
	 * 
	 * @param function A function that take row as parameter
	 * @return DataStore This datastore
	 */
	public function each($cb)
	{
		foreach($this->rows as $index=>$row)
		{
			$result = $cb($row,$index);
			if(is_array($result))
			{
				$this->rows[$index] = $result;
			}
			else if($result===false)
			{
				break;
			}
		}
		return $this;
	}

	/**
	 * Return new datastore with all columns except some
	 * 
	 * Examples
	 * 
	 * $newStore = $store->except("age","city");
	 * 
	 * @param string Column name to be excluded
	 * @return DataStore New datastore containing result
	 */
	public function except()
	{
		$cols = func_get_args();
		//method returns all rows in the collection except for those with the specified keys
		$dstore = new DataStore;
		foreach($this->rows as $row)
		{
			foreach($cols as $col)
			{
				if(isset($row[$col])) unset($row[$col]);
			}
			$dstore->append($row);
		}

		$columnsMeta = Utility::get($this->metaData,"columns");
		if($columnsMeta)
		{
			foreach($cols as $col)
			{
				if(isset($columnsMeta[$col]))
				{
					unset($columnsMeta[$col]);
				}
			}
		}
		$dstore->meta(array("columns"=>$columnsMeta));
		return $dstore;
	}

	/**
	 * Get new datastore containing some of the columns
	 * 
	 * Examples:
	 * 
	 * $newStore = $store->only("name","age");
	 * 
	 * @param string Name of column to be included
	 * @return DataStore New datastore containing result
	 */
	public function only()
	{
		//Only colname
		$cols = func_get_args();
		$dstore = new DataStore;

		foreach($this->rows as $row)
		{
			$new_row = array();
			foreach($cols as $col)
			{
				$new_row[$col] = $row[$col];
			}
			$dstore->push($new_row);
		}
		$columnsMeta = Utility::get($this->metaData,"columns");
		$newColumnsMeta = array();
		if($columnsMeta)
		{
			foreach($cols as $col)
			{
				$newColumnsMeta[$col] = $columnsMeta[$col];
			}
		}
		$dstore->meta(array("columns"=>$newColumnsMeta));
		return $dstore;		
	}

	/**
	 * Get filtered results by function
	 * 
	 * A row will go through if function return true
	 * 
	 * @param function $cb Callback function
	 * @return DataSore New datastore containing filtered results. 
	 */
	public function filterByFunc($cb)
	{
		//method filters the collection using the given callback, keeping only those rows that pass a given truth test
		$dstore = new DataStore;
		$dstore->meta($this->metaData);
		foreach($this->rows as $index=>$row)
		{
			if($cb($row,$index)===true)
			{
				$dstore->append($row);
			}
		}
		return $dstore;
	}

	/**
	 * Return a first row meet a condition defined by callback function
	 * 
	 * $row = $store->first(fucntion($row){
	 * 	return $row["age"]>20;
	 * });
	 * 
	 * @param function $cb Callback function that return true if a row meets condition
	 * @return array The first row that meets condition.
	 */
	public function first($cb=null)
	{
		if($cb==null)
		{
			return $this->isNotEmpty()?$this->rows[0]:null;
		}
		else
		{
			// method returns the first element in the collection that passes a given truth test
			foreach($this->rows as $index=>$row)
			{
				if($cb($row,$index)===true)
				{
					return $row;
				}
			}
		}
		return null;
	}


	/**
	 * Get whether the datastore contains a field
	 * 
	 * @param string $cName Name of a column
	 * @return boolean True if datastore contains specified field
	 */
	public function has($cName)
	{
		//method determines if a given key exists in the collection:
		return isset($this->metaData["columns"][$cName]);
	}

	/**
	 * Return the last row that meets a condition set by callback function
	 * 
	 * Examples:
	 * 
	 * $lastRow = $store->last(function($row){
	 * 		return $row["age"]<65;
	 * });
	 * 
	 * @param function $cb Callback function
	 * @return array The last row that meet conditon
	 */
	public function last($cb=null)
	{
		if($cb==null)
		{
			return $this->isNotEmpty()?$this->rows[$this->count()-1]:null;
		}
		else
		{
			//method returns the last element in the collection that passes a given truth test:
			$count = $this->count();
			for($i=0;$i<$count;$i++)
			{
				if($cb($this->rows[$count-$i],$count-$i)===true)
				{
					return $this->rows[$count-$i];
				}
			}
		}
		return null;		
	}

	/**
	 * Return the mode value of a field
	 * 
	 * @param string $colName Column name
	 * @return float The mode value of the column
	 */
	public function mode($colName)
	{
		$counts = array();
		foreach($this->rows as $row)
		{
			$counts[$row[$colName]] = isset($counts[$row[$colName]])?$counts[$row[$colName]]+1:1;
		}
		
		arsort($counts);
		$list = array_keys($counts);
		return $list[0];
	}

	/**
	 * Return all value of a column in array
	 * 
	 * @param string $colName Column name
	 * @return array Array containing all values of the column
	 */
	public function pluck($colName)
	{
		$result = array();
		foreach($this->rows as $row)
		{
			array_push($result,$row[$colName]);
		}
		return $result;
	}

	/**
	 * Reject some rows that meets condition
	 * 
	 * @param function $cb Callback function
	 * @return DataStore New datastore containing results.
	 */
	public function reject($cb)
	{
		$dstore = new DataStore;
		$dstore->meta($this->metaData);
		foreach($this->rows as $index=>$row)
		{
			if($cb($row,$index)===false)
			{
				$dstore->append($row);
			}
		}
		return $dstore;
	}

	/**
	 * Get slice of data
	 * 
	 * @param integer $offset Starting row to get offset
	 * @param integer $length Number of rows to take, if not specified, all row after $offset will be returned. 
	 * @return DataStore New datastore containing results.
	 */
	public function slice($offset, $length = null)
	{
		return new DataStore(array_slice($this->rows,$offset,$length),$this->metaData);
	}

	/**
	 * Sort the rows of data
	 * 
	 * Examples:
	 * 
	 * $store->sort(array("age"=>"desc"));
	 * 
	 * @param array $sort Condition of sorting
	 * @return DataStore This datastore
	 */
	public function sort($sorts)
	{
		usort($this->rows, function($a, $b) use ($sorts) {
		  $cmp = 0;
		  foreach ($sorts as $sort => $direction) {
			if (is_string($direction)) {
			  $cmp = is_numeric($a[$sort]) && is_numeric($b[$sort]) ? 
				  $a[$sort] - $b[$sort] : strcmp($a[$sort], $b[$sort]);
			  $cmp = $direction === 'asc' ? $cmp : - $cmp;
			}
			else if (is_callable($direction)) 
			  $cmp = $direction($a[$sort], $b[$sort]);
			if ($cmp !== 0) break;
		  }
		  return $cmp;
		});
		return $this;
	}

	/**
	 * Sort data by a key field
	 * 
	 * @param string $key Key that use to sort
	 * @return DataStore This datastore after sorted 
	 */
	public function sortBy($key,$direction='asc')
	{
		return $this->sort(array($key=>$direction));
	}

	/**
	 * Sort asc data rows by column name
	 * 
	 * @return DataStore This datastore after sorted.
	 */
	public function sortKeys()
	{
		if($this->isNotEmpty())
		{
			foreach($this->rows as &$row)
			{
				ksort($row);
			}
		}
		return $this;
	}
	/**
	 * Sort desc data rows by column name
	 * 
	 * @return DataStore This datastore after sorted.
	 */
	public function sortKeysDesc()
	{
		if($this->isNotEmpty())
		{
			foreach($this->rows as &$row)
			{
				krsort($row);
			}
		}
		return $this;
	}

	/**
	 * Splice the data and replace
	 * 
	 * @param integer $offset Starting row to get
	 * @param integer $length How many rows to get
	 * @param array $replacement Optional! Replaced rows
	 * @return DataStore New dataStore containing results. 
	 */
    public function splice($offset, $length = null, $replacement = [])
    {
        if (func_num_args() == 1) {
            return new static(array_splice($this->rows, $offset));
        }

        return new DataStore(array_splice($this->rows, $offset, $length, $replacement),$this->metaData);
    }

	/**
	 * Return top number of rows
	 * 
	 * @param integer $limit Number of rows to take, the limit can be nagative mean take the last number
	 * @return DataStore New datastore containing rows
	 */
	public function take($limit)
	{
        if ($limit < 0) {
            return $this->slice($limit, abs($limit));
        }

        return $this->slice(0, $limit);
	}

	/**
	 * Return all rows
	 * 
	 * @return array All data rows
	 */
	public function all()
	{
		return $this->rows;
	}

	/**
	 * Return rows that has column equal to certain value
	 * 
	 * @param string $key Column name
	 * @param mixed $value
	 * @return DataStore New dataStore containing results
	 */
	public function where($key,$value)
	{
		$dstore= new DataStore;
		$dstore->meta($this->metaData);
		foreach($this->rows as $row)
		{
			if($row[$key]==$value)
			{
				$dstore->append($row);
			}
		}
		return $dstore;
	}

	/**
	 * Return rows that column contains value in an array.
	 * 
	 * @param string $key Column name
	 * @param array $values List of values
	 * @return DataStore New datastore containing results.
	 */
	public function whereIn($key,$values=array())
	{
		$dstore= new DataStore;
		$dstore->meta($this->metaData);
		foreach($this->rows as $row)
		{
			if(in_array($row[$key],$values))
			{
				$dstore->append($row);
			}
		}
		return $dstore;
	}
	/**
	 * Return rows that column does not contain value in an array.
	 * 
	 * @param string $key Column name
	 * @param array $values List of values
	 * @return DataStore New datastore containing results.
	 */
	public function whereNotIn($key,$values)
	{
		$dstore= new DataStore;
		$dstore->meta($this->metaData);
		foreach($this->rows as $row)
		{
			if(!in_array($row[$key],$values))
			{
				$dstore->append($row);
			}
		}
		return $dstore;
	}

	private function mapKeyIndex($arr,$keys)
	{
		$maps = array();
		foreach($arr as $index=>$row)
		{
			$key = "n";
			foreach($keys as $cName)
			{
				$key.=$row[$cName];
			}
			if(!isset($maps[$key]))
			{
				$maps[$key] = array();
			}
			array_push($maps[$key],$index);
		}
		return $maps;
	}

	/**
	 * Join with another datastore on a matching keys
	 * 
	 * Examples
	 * 
	 * $store1->join($store2,array("id"=>"userId"));
	 * 
	 * @param DataStore $secondStore Second datastore
	 * @param array $matching Matching keys
	 * @return DataStore New datastore containing results.
	 */
	public function join($secondStore,$matching)
	{
		$dstore = new DataStore;
		// join with other datasource to produce new one with above condition
		$firstKeys = array_keys($matching);
		$secondKeys = array_values($matching);
		
		
		$firstMaps = $this->mapKeyIndex($this->rows,$firstKeys);
		$secondMaps = $this->mapKeyIndex($secondStore->all(),$secondKeys);
		
		foreach($firstMaps as $key=>$indices)
		{
			if(isset($secondMaps[$key]))
			{
				foreach($indices as $i)
				{
					foreach($secondMaps[$key] as $j)
					{
						$dstore->push(array_merge(
							$this->rows[$i],
							$secondStore->get($j)
						));
					}
				}
			}
		}

		$columnMeta = array_merge($this->metaData["columns"],$secondStore->meta()["columns"]);
		$dstore->meta(array("columns"=>$columnMeta));
		return $dstore;
	}

	/**
	 * Left join with another datastore on matching keys
	 * 
	 * Examples
	 * 
	 * $store1->join($store2,array("id"=>"userId"));
	 * 
	 * @param DataStore $secondStore Second datastore
	 * @param array $matching Matching keys
	 * @return DataStore New datastore containing results.
	 */
	public function leftJoin($secondStore,$matching)
	{
		$dstore = new DataStore;
		// join with other datasource to produce new one with above condition
		$firstKeys = array_keys($matching);
		$secondKeys = array_values($matching);
		
		
		$firstMaps = $this->mapKeyIndex($this->rows,$firstKeys);
		$secondMaps = $this->mapKeyIndex($secondStore->all(),$secondKeys);

		$secondNullRow = array();
		foreach($secondStore->first() as $k=>$v)
		{
			$secondNullRow[$k] = null;
		}
		
		foreach($firstMaps as $key=>$indices)
		{
			foreach($indices as $i)
			{
				if(isset($secondMaps[$key]))
				{
					foreach($secondMaps[$key] as $j)
					{
						$dstore->push(array_merge(
							$this->rows[$i],
							$secondStore->get($j)
						));
					}	
				}
				else
				{
					$dstore->push(array_merge(
						$this->rows[$i],
						$secondNullRow				
					));
				}
			}
		}

		$columnMeta = array_merge($this->metaData["columns"],$secondStore->meta()["columns"]);
		$dstore->meta(array("columns"=>$columnMeta));
		return $dstore;	
	}

	/**
	 * Add extra column meta to existing
	 * 
	 * Examples
	 * 
	 * $store1->columnMeta(array(
	 * 		"age"=>array(
	 * 			"name"=>"Age"
	 * 		)
	 * ))
	 * 
	 * @param DataStore $secondStore Second datastore
	 * @param array $matching Matching keys
	 * @return DataStore New datastore containing results.
	 */
	public function columnMeta($settings)
	{
		foreach($settings as $cName=>$cMeta)
		{
			if(isset($this->metaData["columns"]) && $this->metaData["columns"][$cName])
			{
				$this->metaData["columns"][$cName] = array_merge(
					$this->metaData["columns"][$cName],
					$cMeta
				);
			}	
		}
		return $this;
	}
	/**
	 * Implement for IteratorAggregate
	 */
    public function getIterator() {
        return new \ArrayIterator($this->rows);
	}
	/**
	 * Implement offsetSet for ArrayAccess interface
	 */
	public function offsetSet($index, $row) 
	{
        if (is_null($index)) {
            $this->rows[] = $row;
        } else {
            $this->rows[$index] = $row;
        }
    }

	/**
	 * Implement offsetExists for ArrayAccess interface
	 */
    public function offsetExists($index) {
        return isset($this->rows[$index]);
    }

	/**
	 * Implement offsetUnset for ArrayAccess interface
	 */
    public function offsetUnset($index) {
        unset($this->rows[$index]);
    }

	/**
	 * Implement offsetGet for ArrayAccess interface
	 */
    public function offsetGet($index) {
        return isset($this->rows[$index]) ? $this->rows[$index] : null;
	}	
}