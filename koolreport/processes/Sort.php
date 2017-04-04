<?php
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
  private $data = array();
	
	protected function onInput($row)
	{
		array_push($this->data, $row);
	}
  
  function process(& $data)
  {
    $sorts = $this->params;
    usort($data, function($a, $b) use ($sorts) {
      $cmp = 0;
      foreach ($sorts as $sort => $direction) {
        if ($a[$sort] < $b[$sort]) {
          $cmp = $direction === 'asc' ? -1 : 1;
          break;
        }
        else if ($a[$sort] > $b[$sort]) {
          $cmp = $direction === 'desc' ? -1 : 1;
          break;
        }      
      }
      
      return $cmp;
    });
  }
  
  public function onInputEnd()
  {
    $this->process($this->data);
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