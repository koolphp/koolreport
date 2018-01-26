<?php
/**
 * This file contains process to limit the rows return from datasource
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * ->pipe(new Limit(array(10,5)))
 * 
 * Limit number of row to 10 and starting from row 5
 * 
 */
namespace koolreport\processes;
use \koolreport\core\Process;
use \koolreport\core\Utility;

class Limit extends Process
{
	protected $index=-1;
	protected $limit;
	protected $offset;
	
	protected function onInit()
	{
		$this->limit = Utility::get($this->params,0,10);
		$this->offset = Utility::get($this->params,1,0);
	}
	
	protected function onStartInput()
	{
		$this->index = -1;
	}
	
	protected function onInput($data)
	{
		$this->index++;
		if($this->index>=$this->offset && $this->index<$this->offset+$this->limit)
		{
			$this->next($data);	
		}
	}
}