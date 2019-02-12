<?php
/**
 * This file contains class to transpose column and row of table
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/*
->pipe(new Transpose())

->pipe(new Transpose(array(
"firstColumnAs
)))

 */

namespace koolreport\processes;

use \koolreport\core\Process;

/**
 * This file contains class to transpose column and row of table
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class Transpose extends Process
{
    protected $data;

    /**
     * Handle on initiation
     *
     * @return null
     */
    protected function onInit()
    {
        $this->data = array();
    }

    /**
     * Handle on meta data received
     * 
     * @param array $metaData The meta data received
     * @param Node  $source   The source sending data
     * 
     * @return null
     */
    public function receiveMeta($metaData, $source)
    {
        $this->streamingSource = $source;
        $this->metaData = $metaData;
    }

    /**
     * Handle on data input
     *
     * @param array $row The input data row
     *
     * @return null
     */
    protected function onInput($row)
    {
        array_push($this->data, $row);
    }

    /**
     * Handle on data input end
     * 
     * @return null
     */
    protected function onInputEnd()
    {
        //Send meta
        $countRow = count($this->data);
        $newMeta = array(
            "columns" => array(),
        );
        for ($i = 0; $i <= $countRow; $i++) {
            $newMeta["columns"]["c$i"] = array("type" => "unknown");
        }
        $newMeta["columns"]["c0"]["type"] = "string";
        $this->sendMeta($newMeta);

        $keys = ($countRow > 0) 
            ? array_keys($this->data[0]) : array_keys($this->metaData["columns"]);

        //Send each rows
        foreach ($keys as $cKey) {
            $row = array("c0" => isset($this->metaData["columns"][$cKey]["label"]) 
                ? $this->metaData["columns"][$cKey]["label"] : $cKey);
            for ($i = 0; $i < $countRow; $i++) {
                $row["c" . ($i + 1)] = $this->data[$i][$cKey];
                unset($this->data[$i][$cKey]);
            }
            $this->next($row);
        }
    }
}
