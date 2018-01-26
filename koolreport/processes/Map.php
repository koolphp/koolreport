<?php
/**
 * This file contains class to map data value to another.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
  ->pipe(new Map(array(
    '{value}' => function($row, $metaData) {
      return $newRows;
    },
    '{meta}' => function($metaData) {
      return $newMeta;
    },
  )))
 * 
 * 
 */
namespace koolreport\processes;
use \koolreport\core\Process;
use \koolreport\core\Utility;

class Map extends Process
{
  protected $metaSent = false;
  
  public function receiveMeta($metaData, $source)
	{
		$this->streamingSource = $source;
		$this->metaData = $metaData;
    if (isset($this->params['{meta}']) 
        && is_callable($this->params['{meta}'])) {
      $func = $this->params['{meta}'];
      $newMeta = $func($metaData);
      // Utility::prettyPrint($newMeta);
      $this->sendMeta($newMeta);
      $this->metaSent = true;
    }
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
			if(strpos($type,$key)!==false)
				return $value;
		return "unknown";
	}
  
	protected function onInput($row)
	{
    if (isset($this->params['{value}']) 
        && is_callable($this->params['{value}'])) {
      $func = $this->params['{value}'];
      $newRows = $func($row, $this->metaData);
      // Utility::prettyPrint($newRows);
      if (! $this->metaSent) {
        $newMeta = $this->metaData;
        $colMetas = $newMeta['columns'];
        if (isset($newRows[0])) {
          $newRow = $newRows[0];
          $newColumns = array_keys($newRow);
          foreach ($newColumns as $newCol)
            if (! isset($colMetas[$newCol]))
              $colMetas[$newCol] = array(
                'type' => $this->guessType($newRow[$newCol])
              );
          // foreach ($colMetas as $colName => $colMeta)
            // if (! isset($newRow[$colName]))
              // unset($colMetas[$colName]);
        }
        $newMeta['columns'] = $colMetas;
        $this->sendMeta($newMeta);
        $this->metaSent = true;
      }
      if (is_array($newRows))
        foreach ($newRows as $newRow)
          $this->next($newRow);
    }
    else
      $this->next($row);
	}
}
