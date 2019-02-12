<?php
/**
 * This file contains class to format the date time column
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
 * ->pipe(new DateTimeFormat(array(
 *         "last_login"=>array(
 *             "from"=>"Y-m-d H:i:s",
 *             "to"=>"F j, Y"
 *         ),
 *         "created_time"=>"F j,Y"
 * )))
 *
 */
namespace koolreport\processes;

use \koolreport\core\Process;
use \koolreport\core\Utility;

/**
 * This file contains class to format the date time column
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class DateTimeFormat extends Process
{
    /**
     * Process initiation
     * 
     * @return null
     */
    protected function onInit()
    {
        foreach ($this->params as $cName => $cParams) {
            if (gettype($cParams) == "string") {
                $this->params[$cName] = array(
                    "to" => $cParams,
                );
            }
        }
    }

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
            $from = Utility::get($cParams, "from");
            if (!$from) {
                $from = Utility::get(
                    $metaData["columns"][$cName],
                    "format",
                    "Y-m-d H:i:s"
                );
            }
            $to = Utility::get($cParams, "to", "Y-m-d H:i:s");

            $this->params[$cName] = array(
                "from" => $from,
                "to" => $to,
            );
            $metaData["columns"][$cName] = array_merge(
                $metaData["columns"][$cName],
                array(
                    "type" => "datetime",
                    "format" => $to,
                )
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
            $from = Utility::get($cParams, "from", "Y-m-d H:i:s");
            $to = Utility::get($cParams, "to", "Y-m-d H:i:s");
            if ($data[$cName] && $from != $to) {
                $obj = \DateTime::createFromFormat($from, $data[$cName]);
                $data[$cName] = ($obj) ? $obj->format($to) : null;
            }
        }
        $this->next($data);
    }
}
