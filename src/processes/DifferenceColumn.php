<?php
/**
 * This file contains process to create difference column from a column
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * ->pipe(new DiffernceColumn(array(
 * 		"diff"=>"amount",
 * )))
 * 
 */
namespace koolreport\processes;
use \koolreport\core\Utility;
use \koolreport\core\Process;
 
class DifferenceColumn extends Process
{
    protected $previousRow;
	protected function onMetaReceived($metaData)
	{
		foreach($this->params as $copy=>$original)
		{
            $metaData["columns"][$copy] = $metaData["columns"][$original];
            $metaData["columns"][$copy]["type"]="number";
		}
		return $metaData;
    }
    
    public function onInputStart()
    {
        $this->previousRow = null;
    }
	
	public function onInput($row)
	{
		//Process data here
		foreach($this->params as $copy=>$original)
		{
			$row[$copy] = $row[$original] - Utility::get($this->previousRow,$original,0);
        }
        $this->previousRow = $row;
		$this->next($row);
	}
}