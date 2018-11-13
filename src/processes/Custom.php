<?php
/**
 * This file contain class to add custom modification to data.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\processes;
use \koolreport\core\Process;

class Custom extends Process
{
	
	protected function onInput($data)
	{
		$func = $this->params;

		$data = $func($data);
		if($data)
		{
			$this->next($data);
		}
	}
}