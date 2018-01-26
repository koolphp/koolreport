<?php
/**
 * This file contains class to handle data sorting
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * ->pipe(new Sort(array(
 * 		"amount"=>"desc",
 * 		"id"=>"asc"
 * )))
 *  */
namespace koolreport\processes;
use \koolreport\core\Process;

class Sort extends Process
{
  protected $data = array();
	
	protected function onInput($row)
	{
		array_push($this->data, $row);
	}
  
  function sortProcess()
  {
    $sorts = $this->params;
    usort($this->data, function($a, $b) use ($sorts) {
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
  }
  
  public function onInputEnd()
  {
    $this->sortProcess();
    // echo "<pre>";
    // echo json_encode($this->data, JSON_PRETTY_PRINT); 
    // echo "</pre>";
    // echo '<br>';
    foreach($this->data as $row)
    {
      $this->next($row);
    }	
    unset($this->data);
  }
}