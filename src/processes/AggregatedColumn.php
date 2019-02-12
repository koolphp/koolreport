<?php
/**
 * This file contains class to generate aggregate columns.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

 /* Usage:
 
    ->pipe(new \koolreport\processes\AggregatedColumn(array(
        'Q1 Sales Sum' => [
            'sum', 'Q1 Sales'
        ]
    )))
 */

namespace koolreport\processes;
use \koolreport\core\Utility;
use \koolreport\core\Process;

/**
 * This file contains class to generate aggregate columns.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class AggregatedColumn extends Process
{
    /**
     * Data
     * 
     * @var array Data
     */
    protected $data;

    /**
     * Handle on process init
     * 
     * @return null
     */
    protected function onInit()
    {
        $this->data = array();
    }

    /**
     * Handle on meta received
     * 
     * @param array $metaData The metadata
     * 
     * @return array New meta data
     */
    protected function onMetaReceived($metaData)
    {
        foreach ($this->params as $cKey=>$cValue) {
            $metaData["columns"][$cKey] = array(
                "type"=>"number",
            );
        }
        return $metaData;
    }

    /**
     * Handle on data input
     * 
     * @param array $row The row data
     * 
     * @return null
     */
    protected function onInput($row)
    {
        array_push($this->data, $row);
    }

    /**
     * Handle on input end
     * 
     * @return null
     */
    protected function onInputEnd()
    {
        $countRows = count($this->data);
        $storage = array();
        foreach ($this->params as $cKey=>$cValue) {
            $storage[$cKey] = array(
                "count"=>$countRows,
                "sum"=>0,
                "min"=>INF,
                "max"=>-INF,
            );
        }

        foreach ($this->data as $row) {
            foreach ($this->params as $cKey=>$cValue) {
                switch(strtolower($cValue[0]))
                {
                case "sum":
                case "avg":
                    $storage[$cKey]["sum"]+=$row[$cValue[1]];
                    break;
                case "min":
                    if ($storage[$cKey]["min"]>$row[$cValue[1]]) {
                        $storage[$cKey]["min"] = $row[$cValue[1]];
                    }
                    break;
                case "max":
                    if ($storage[$cKey]["max"]<$row[$cValue[1]]) {
                        $storage[$cKey]["max"] = $row[$cValue[1]];
                    }
                    break;
                }
            }
        }

        foreach ($this->data as &$row) {
            foreach ($this->params as $cKey=>$cValue) {
                switch(strtolower($cValue[0]))
                {
                case "count":
                    $row[$cKey] = $storage[$cKey]["count"];
                    break;
                case "sum":
                    $row[$cKey] = $storage[$cKey]["sum"];
                    break;
                case "min":
                    $row[$cKey] = $storage[$cKey]["min"];
                    break;
                case "max":
                    $row[$cKey] = $storage[$cKey]["max"];
                    break;
                case "avg":
                    if (!isset($storage[$cKey]["avg"])) {
                        $storage[$cKey]["avg"] = $storage[$cKey]["sum"]/$storage[$cKey]["count"];
                    }
                    $row[$cKey] = $storage[$cKey]["avg"];
                    break;
                case "acml":
                    if (!isset($storage[$cKey]["acml"])) {
                        $storage[$cKey]["acml"] = 0;
                    }
                    $row[$cKey] = $storage[$cKey]["acml"]+$row[$cValue[1]];
                    $storage[$cKey]["acml"] = $row[$cKey];
                    break;
                }
            }
        }
        while ($item = array_shift($this->data)) {
            $this->next($item);
        }
    }
}