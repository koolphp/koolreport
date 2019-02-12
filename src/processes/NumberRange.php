<?php
/**
 * This file contains definition for number range
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
 * ->pipe(new NumberRange(array(
 *         "sale"=>array(
 *             "high"=>array(50,null),
 *             "medium"=>array(10,50),
 *             "low"=>array(null,10)
 *         )
 * )))
 *
 */
namespace koolreport\processes;

use \koolreport\core\Process;
use \koolreport\core\Utility;

/**
 * This file contains definition for number range
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class NumberRange extends Process
{

    /**
     * Handle on meta received
     * 
     * @param array $metaData The meta data
     * 
     * @return array New meta data
     */
    protected function onMetaReceived($metaData)
    {
        foreach ($this->params as $cName => $cParams) {
            $metaData["columns"][$cName] = array(
                "type" => "string",
            );
        }
        return $metaData;
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
        foreach ($this->params as $cName => $cParams) {
            $value = null;
            foreach ($cParams as $cValue => $criteria) {
                $from = Utility::get($criteria, 0);
                $to = Utility::get($criteria, 1);
                if (($from == null || $from <= $data[$cName]) && ($to == null || $data[$cName] < $to)) {
                    $value = $cValue;
                }
            }
            $data[$cName] = ($value) ? $value : null;
        }
        $this->next($data);
    }
}
