<?php
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