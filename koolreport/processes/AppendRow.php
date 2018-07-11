<?php
/**
 * This file contains process to append row/rows to data stream
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * ->pipe(new AppendRow(
 * 		array("name"=>"John")
 * ))
 * 
 * or multiple row
 * 
 * ->pipe(new AppendRow(array(
 * 		array("name"=>"John"),
 * 		array("name"=>"Marry"),
 * )))
 */
namespace koolreport\processes;
use \koolreport\core\Process;
use \koolreport\core\Utility;

class AppendRow extends Process
{
	protected function onInputEnd()
	{
		$data = array();
		if(Utility::isAssoc($this->params))
		{
			$data = array($this->params);
		}
		else
		{
			$data = $this->params;
		}
		foreach($data as $row)
		{
			$this->next($row);
		}
	}
}