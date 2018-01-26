<?php
/**
 * This file contains class to pull data from CSV file
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/*
 * The CSV will load the CSV data, breaking down to columns and try to determine
 * the type for the columns, the precision contain number of rows to run to determine
 * the meta data for columns.
 * 
 * $firstRowData: is the first row data, usually is false, first row is column name
 * if the firstRowData is true, name column as column 1, column 2
 * 
 */
namespace koolreport\datasources;
use \koolreport\core\DataSource;
use \koolreport\core\Utility;

class CSVDataSource extends DataSource
{
	protected $filePath;
	protected $fieldSeparator;
	protected $precision;
	protected $charset;
	protected $firstRowData;
	
	protected function onInit()
	{
		$this->filePath = Utility::get($this->params,"filePath");
		$this->fieldSeparator = Utility::get($this->params,"fieldSeparator",",");
		$this->charset = Utility::get($this->params,"charset","utf8");
		$this->precision = Utility::get($this->params,"precision",100);
		$this->firstRowData = Utility::get($this->params,"firstRowData",false);
	}
  
  protected function guessType($value)
	{
		$map = array(
			"float"=>"number",
			"double"=>"number",
			"int"=>"number",
			"integer"=>"number",
			"bool"=>"number",
			"numeric"=>"number",
			"string"=>"string",
		);

		$type = strtolower(gettype($value));
		foreach($map as $key=>$value)
		{
			if(strpos($type,$key)!==false)
			{
				return $value;
			}			
		}
		return "unknown";
	}
	
	public function start()
	{
    $data = array();
    if (($handle = fopen($this->filePath, "r")) !== FALSE) {
      $row = fgetcsv($handle, 0, $this->fieldSeparator);
      if (is_array($row)) {
        if (! $this->firstRowData)
          $columnNames = $row;
        else {
          $columnNames = array();
          for ($i=0; $i<count($row); $i++)
            array_push($columnNames, 'Column ' . $i);
				}
				
        $metaData = array("columns"=>array());
        for($i=0;$i<count($columnNames);$i++) {						
          $metaData["columns"][$columnNames[$i]] = array(
            "type"=>(isset($row)) ? $this->guessType($row[$i]) : "unknown");
        }
        $this->sendMeta($metaData,$this);
        $this->startInput(null);
        
        if ($this->firstRowData)
          $this->next(array_combine($columnNames, $row), $this);
      }
      while (($row = fgetcsv($handle, 0, $this->fieldSeparator)) !== FALSE) {
        $this->next(array_combine($columnNames, $row), $this);
      }
    }
    else {
      echo 'fopen failed<br>';
    }
    $this->endInput(null);
	}
}
