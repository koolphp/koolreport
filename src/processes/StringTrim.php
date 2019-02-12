<?php
/**
 * This file contains class that help to remove space or unwanted character at the beginning and end of data cell.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
 * ->pipe(new StringTrim(array(
 *         "name","address",
 *         "character_mask"=>"\t\n\r\0\x0B"
 * )))
 * ->pipe(new StringTrim()): Trim all possible column
 * ->pipe(new StringTrim(array("name","address"))): trim space by default if without character mask
 *
 */
namespace koolreport\processes;

use \koolreport\core\Process;

/**
 * This file contains class that help to remove space or unwanted character at the beginning and end of data cell.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class StringTrim extends Process
{
    /**
     * List of masked character
     * 
     * @param string List of masked character
     */
    protected $character_mask;
    
    /**
     * Handle on initiation
     *
     * @return null
     */
    protected function onInit()
    {
        if (isset($this->params["character_mask"])) {
            $this->character_mask = $this->params["character_mask"];
            unset($this->params["character_mask"]);
        }
    }

    /**
     * Handle on data input
     *
     * @param array $data The input data row
     *
     * @return null
     */
    protected function onInput($data)
    {
        //Process data here
        foreach ($this->params as $column) {
            if ($this->character_mask) {
                $data[$column] = trim($data[$column], $this->character_mask);
            } else {
                $data[$column] = trim($data[$column]);
            }
        }
        $this->next($data);
    }
}
