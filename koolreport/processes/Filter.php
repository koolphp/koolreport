<?php
/* Usage
 * ->pipe(new Filter(array(
 * 		array('age','>',4),
 * 		array('name','contains','Tuan'),
 * 		array('time','<=','2010-12-31')
 * )))
 */
namespace koolreport\processes;
use \koolreport\core\Process;

class Filter extends Process
{
  private $filters;
  
  public function __construct($filters)
  {
    parent::__construct();
    
    $this->filters = $filters;
  }
  
  function isFiltered($filter, $value, $type)
  {
    $isFiltered = true;
    switch ($filter[1]) {
      case '=':
        if ($type === 'string')
          $isFiltered = strcmp($value, $filter[2]) == 0;
        else
          $isFiltered = $value == $filter[2];
        break;
      case '!=':
        if ($type === 'string')
          $isFiltered = strcmp($value, $filter[2]) != 0;
        else
          $isFiltered = $value != $filter[2];
        break;
      case '>':
        if ($type === 'string')
          $isFiltered = strcmp($value, $filter[2]) > 0;
        else
          $isFiltered = $value > $filter[2];
        break;
      case '>=':
        if ($type === 'string')
          $isFiltered = strcmp($value, $filter[2]) >= 0;
        else
          $isFiltered = $value >= $filter[2];
        break;
      case '<':
        if ($type === 'string')
          $isFiltered = strcmp($value, $filter[2]) < 0;
        else
          $isFiltered = $value < $filter[2];
        break;
      case '<=':
        if ($type === 'string')
          $isFiltered = strcmp($value, $filter[2]) <= 0;
        else
          $isFiltered = $value <= $filter[2];
        break;
      case 'contain':
        $isFiltered = strpos(strtolower($value), strtolower($filter[2])) !== false;
        break;
      case 'notContain':
        $isFiltered = strpos(strtolower($value), strtolower($filter[2])) === false;
        break;
      case 'startWith':
        $isFiltered = strpos(strtolower($value), strtolower($filter[2])) === 0;
        break;
      case 'notStartWith':
        $isFiltered = strpos(strtolower($value), strtolower($filter[2])) !== 0;
        break;
      case 'endWith':
        $isFiltered = strpos(strrev(strtolower($value)), strrev(strtolower($filter[2]))) === 0;
        break;
      case 'notEndWith':
        $isFiltered = strpos(strrev(strtolower($value)), strrev(strtolower($filter[2]))) !== 0;
        break;
      case 'between':
        $isFiltered = $value > $filter[2] && $value < $filter[3];
        break;
      case 'notBetween':
        $isFiltered = ! ($value > $filter[2] && $value < $filter[3]);
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
    $isFiltered = true;
    foreach ($filters as $filter) {
      $field = $filter[0];
      $type = $columnsMeta[$field]['type'];
      if (isset($data[$field]))  
        $isFiltered = $isFiltered 
            && $this->isFiltered($filter, $data[$field], $type);
    }
    if ($isFiltered) {
      $this->next($data);
    }
	}
}