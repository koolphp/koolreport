<?php
/**
 * This file contains process to filter rows based on condition.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
   ->pipe(new Filter(
      'or',
      array('age','>',4),
      array('name','contains','Tuan'),
      'and',
      array('time','<=','2010-12-31')),
    )
 */
namespace koolreport\processes;
use \koolreport\core\Utility;
use \koolreport\core\Process;

class Filter extends Process
{
  protected $conditions;
  protected $logicalOperator;
  
  function onInit()
  {
    $this->filters = isset($this->params) ? $this->params : array();
  }
  
  function isFiltered($condition, $value, $type)
  {
    $isFiltered = true;
    switch ($condition[1]) {
      case '=':
        if ($type === 'string')
          $isFiltered = strcmp($value, $condition[2]) == 0;
        else
          $isFiltered = $value == $condition[2];
        break;
      case '!=':
        if ($type === 'string')
          $isFiltered = strcmp($value, $condition[2]) != 0;
        else
          $isFiltered = $value != $condition[2];
        break;
      case '>':
        if ($type === 'string')
          $isFiltered = strcmp($value, $condition[2]) > 0;
        else
          $isFiltered = $value > $condition[2];
        break;
      case '>=':
        if ($type === 'string')
          $isFiltered = strcmp($value, $condition[2]) >= 0;
        else
          $isFiltered = $value >= $condition[2];
        break;
      case '<':
        if ($type === 'string')
          $isFiltered = strcmp($value, $condition[2]) < 0;
        else
          $isFiltered = $value < $condition[2];
        break;
      case '<=':
        if ($type === 'string')
          $isFiltered = strcmp($value, $condition[2]) <= 0;
        else
          $isFiltered = $value <= $condition[2];
        break;
      case 'contain':
        $isFiltered = strpos(strtolower($value), strtolower($condition[2])) !== false;
        break;
      case 'notContain':
        $isFiltered = strpos(strtolower($value), strtolower($condition[2])) === false;
        break;
      case 'startWith':
        $isFiltered = strpos(strtolower($value), strtolower($condition[2])) === 0;
        break;
      case 'notStartWith':
        $isFiltered = strpos(strtolower($value), strtolower($condition[2])) !== 0;
        break;
      case 'endWith':
        $isFiltered = strpos(strrev(strtolower($value)), strrev(strtolower($condition[2]))) === 0;
        break;
      case 'notEndWith':
        $isFiltered = strpos(strrev(strtolower($value)), strrev(strtolower($condition[2]))) !== 0;
        break;
      case 'between':
        $isFiltered = $value > $condition[2] && $value < $condition[3];
        break;
      case 'notBetween':
        $isFiltered = ! ($value > $condition[2] && $value < $condition[3]);
        break;
      default:
        break;
    }
    return $isFiltered;
  }
	
	protected function onInput($data)
	{
    $columnsMeta = $this->metaData['columns'];
    $filters = $this->filters;
    $logicalOperator = 'and';
    $isFiltered = true;
    foreach ($filters as $i => $filter) {
      if (is_array($filter)) {
        $field = $filter[0];
        $type = $columnsMeta[$field]['type'];
        if (! isset($data[$field]))
          continue;
        $filterResult = $this->isFiltered($filter, $data[$field], $type);
        if ($logicalOperator === 'and') {
          $isFiltered = $isFiltered && $filterResult;
        }
        if ($logicalOperator === 'or') {
          $isFiltered = $isFiltered || $filterResult;
        } 
      }
      else if ($filter === 'and' || $filter === 'or') {
        $logicalOperator = $filter;
        if ($filter === 'or' && $i === 0)
          $isFiltered = false;
      }
    }
    if ($isFiltered) {
      $this->next($data);
    }
	}
}