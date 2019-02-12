<?php
/**
 * This file contains class to turn number data into group
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
 * ->pipe(new NumberBucket(array(
 *         "sale"=>array(
 *             "step"=>5,
 *             "formatString"=>"{from} - {to}"
 *             "decimals"=>0,
 *             "thousandSeparator"=>",",
 *             "decimalPoint"=>".",
 *             "prefix"=>"",
 *             "suffix"=>"",
 *         )
 * )))
 *
 */
namespace koolreport\processes;

use \koolreport\core\Process;
use \koolreport\core\Utility;

/**
 * This file contains class to turn number data into group
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class NumberBucket extends Process
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
        foreach ($this->params as $cName => $cParam) {
            $metaData["columns"][$cName] = array(
                "type" => "string",
            );
            $this->params[$cName]["formatString"] = Utility::get($cParam, "formatString", "{from} - {to}");
            $this->params[$cName]["decimals"] = Utility::get($cParam, "decimals", 0);
            $this->params[$cName]["thousandSeparator"] = Utility::get($cParam, "thousandSeparator", ",");
            $this->params[$cName]["decimalPoint"] = Utility::get($cParam, "decimalPoint", ".");
            $this->params[$cName]["prefix"] = Utility::get($cParam, "prefix", "");
            $this->params[$cName]["suffix"] = Utility::get($cParam, "suffix", "");
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
        foreach ($this->params as $cName => $cParam) {
            $numberFormat = array_merge($cParam, array("type" => "number"));
            //print_r($numberFormat);
            $from = Utility::format(floor($data[$cName] / $cParam["step"]) * $cParam["step"], $numberFormat);
            $to = Utility::format(floor($data[$cName] / $cParam["step"] + 1) * $cParam["step"], $numberFormat);

            $data[$cName] = Utility::strReplace(
                $cParam["formatString"],
                array(
                    "{from}" => $from,
                    "{to}" => $to,
                )
            );
        }
        $this->next($data);
    }
}
