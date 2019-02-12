<?php
/**
 * This file contains process to create difference column from a column
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
 * ->pipe(new DiffernceColumn(array(
 *         "diff"=>"amount",
 * )))
 *
 */
namespace koolreport\processes;

use \koolreport\core\Process;
use \koolreport\core\Utility;

/**
 * This file contains process to create difference column from a column
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class DifferenceColumn extends Process
{
    protected $previousRow;

    /**
     * Handle on meta received
     * 
     * @param array $metaData The meta data
     * 
     * @return array New meta data
     */
    protected function onMetaReceived($metaData)
    {
        foreach ($this->params as $copy => $original) {
            $metaData["columns"][$copy] = $metaData["columns"][$original];
            $metaData["columns"][$copy]["type"] = "number";
        }
        return $metaData;
    }

    /**
     * Handle on input start
     * 
     * @return null
     */
    public function onInputStart()
    {
        $this->previousRow = null;
    }

    /**
     * Handle on data input
     *
     * @param array $row The input data row
     *
     * @return null
     */
    public function onInput($row)
    {
        //Process data here
        foreach ($this->params as $copy => $original) {
            $row[$copy] = $row[$original] - Utility::get($this->previousRow, $original, 0);
        }
        $this->previousRow = $row;
        $this->next($row);
    }
}
