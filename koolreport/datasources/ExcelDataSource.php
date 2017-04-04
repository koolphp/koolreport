<?php
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

class ExcelDataSource extends DataSource
{
	protected $filePath;
	protected $charset;
	protected $firstRowData;
	
	protected function onInit()
	{
		$this->filePath = Utility::get($this->params,"filePath");
		$this->charset = Utility::get($this->params,"charset","utf8");
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
    $excelReader = \PHPExcel_IOFactory::createReaderForFile($this->filePath);
    $excelObj = $excelReader->load($this->filePath);
    
    $sheet = $excelObj->getActiveSheet();
    $highestRow = $sheet->getHighestRow(); 
    $highestColumn = $sheet->getHighestColumn();
    
    $firstRow = $sheet->rangeToArray(
        'A1:' . $highestColumn . '1',
        NULL,TRUE,FALSE
    )[0];
    $colNum = 0;
    foreach ($firstRow as $col => $text)
      if (empty($text)) {
        $colNum = $col;
        break;
      }
    $colNum = \PHPExcel_Cell::stringFromColumnIndex($colNum - 1);
    $rowNum = $highestRow;
    
    $i = 1;
    $row = $sheet->rangeToArray(
      // 'A1:' . $colNum . $rowNum,
      "A$i:" . $colNum . $i, NULL,TRUE,FALSE
    )[0];
    // print_r($row); echo '<br';
    if (is_array($row)) {
      if (! $this->firstRowData)
        $columnNames = $row;
      else {
        $columnNames = array();
        if (isset($row))
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
    
    for($i=2; $i<$rowNum+1; $i++) {
      $row = $sheet->rangeToArray(
        "A$i:" . $colNum . $i, NULL,TRUE,FALSE
      )[0];
      $this->next(array_combine($columnNames, $row), $this);	
    }
    $this->endInput(null);
	}
}
