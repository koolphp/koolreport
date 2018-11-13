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
    /**
     * @var string $filePath The path to csv file
     */
    protected $filePath;
    
    /**
     * @var string $fieldSeparator The seperator between field in file
     */
    protected $fieldSeparator;

    /**
     * @var integer The number of rows used to guess type of column
     */
    protected $precision;

    /**
     * @var string $charset Set charset
     */
    protected $charset;

    /**
     * @var bool $firstRowData Whether first row is data or columnName
     */
    protected $firstRowData;

    /**
     * Init the datasource
     */
    protected function onInit()
    {
        $this->filePath = Utility::get($this->params, "filePath");
        $this->fieldSeparator = Utility::get($this->params, "fieldSeparator", ",");
        $this->charset = Utility::get($this->params, "charset");
        $this->precision = Utility::get($this->params, "precision", 100);
        $this->firstRowData = Utility::get($this->params, "firstRowData", false);
    }

    /**
     * Guess data type
     * 
     * @param mixed $value The value
     * @return string The type of value
     */
    protected function guessType($value)
    {
        $map = array(
            "float" => "number",
            "double" => "number",
            "int" => "number",
            "integer" => "number",
            "bool" => "number",
            "numeric" => "number",
            "string" => "string",
        );

        $type = strtolower(gettype($value));
        foreach ($map as $key => $value) {
            if (strpos($type, $key) !== false) {
                return $value;
            }
        }
        return "unknown";
    }

    /**
     * Start piping data
     */
    public function start()
    {
        // $offset = 0;
        // //Go to where we were when we ended the last batch
        // fseek($fileHandle, $offset);
        // fgetcsv($fileHandle)
        // $offset = ftell($fileHandle);
            
        $data = array();
        if (($handle = fopen($this->filePath, "r")) !== false) {
            $row = fgetcsv($handle, 0, $this->fieldSeparator);
            //Convert to UTF8 if assign charset to utf8
            $row = array_map(function($item){
                return ($this->charset=="utf8" && is_string($item))?utf8_encode($item):$item;
            },$row);

            if (is_array($row)) {
                if (!$this->firstRowData) {
                    $columnNames = $row;
                } else {
                    $columnNames = array();
                    for ($i = 0; $i < count($row); $i++) {
                        array_push($columnNames, 'Column ' . $i);
                    }

                }

                $metaData = array("columns" => array());
                for ($i = 0; $i < count($columnNames); $i++) {
                    $metaData["columns"][$columnNames[$i]] = array(
                        "type" => (isset($row)) ? $this->guessType($row[$i]) : "unknown");
                }
                $this->sendMeta($metaData, $this);
                $this->startInput(null);

                if ($this->firstRowData) {
                    $this->next(array_combine($columnNames, $row), $this);
                }
            }
            while (($row = fgetcsv($handle, 0, $this->fieldSeparator)) !== false) {
                $row = array_map(function($item){
                    return ($this->charset=="utf8" && is_string($item))?utf8_encode($item):$item;
                },$row);    
                $this->next(array_combine($columnNames, $row), $this);
            }
        } else {
            throw new \Exception('Failed to open ' . $this->filePath);
        }
        $this->endInput(null);
    }
}
