<?php
/**
 * This file contains process to create accumulative column from a column
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
 * ->pipe(new AccumulativeColumn(array(
 * 		"accumulativeAmount"=>"amount",
 * )))
 * 
 */
namespace koolreport\processes;
use \koolreport\core\Utility;
use \koolreport\core\Process;
 
/**
 * This file contains process to create accumulative column from a column
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class AccumulativeColumn extends Process
{
    /**
     * The previous row
     * 
     * @var array The previous row
     */
    protected $previousRow;
    
    /**
     * Handle on meta received
     * 
     * @param array $metaData Metadata
     * 
     * @return array New metadata
     */
    protected function onMetaReceived($metaData)
    {
        foreach ($this->params as $copy=>$original) {
            $metaData["columns"][$copy] = $metaData["columns"][$original];
            $metaData["columns"][$copy]["type"]="number";
        }
        return $metaData;
    }
    
    /**
     * Handle input start
     * 
     * @return null
     */
    public function onInputStart()
    {
        $this->previousRow = null;
    }
    
    /**
     * Handle on input
     * 
     * @param array $row The data row
     * 
     * @return null
     */
    public function onInput($row)
    {
        //Process data here
        foreach ($this->params as $copy=>$original) {
            $row[$copy] = $row[$original] + Utility::get($this->previousRow, $copy, 0);
        }
        $this->previousRow = $row;
        $this->next($row);
    }
}
