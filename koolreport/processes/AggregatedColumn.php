<?php
/**
 * This file contains class to generate aggregate columns.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\processes;
use \koolreport\core\Utility;
use \koolreport\core\Process;

class AggregatedColumn extends Process
{
    protected $data;

    protected function onInit()
    {
        $this->data = array();
    }

    protected function onMetaReceived($metaData)
    {
        foreach($this->params as $cKey=>$cValue)
        {
            $metaData["columns"][$cKey] = array(
                "type"=>"number",
            );
        }
        return $metaData;
    }

    protected function onInput($row)
    {
        array_push($this->data,$row);
    }

    protected function onInputEnd()
    {
        $countRows = count($this->data);
        $storage = array();
        foreach($this->params as $cKey=>$cValue)
        {
            $storage[$cKey] = array(
                "count"=>$countRows,
                "sum"=>0,
                "min"=>INF,
                "max"=>-INF,
            );
        }

        foreach($this->data as $row)
        {
            foreach($this->params as $cKey=>$cValue)
            {
                switch(strtolower($cValue[0]))
                {
                    case "sum":
                    case "avg":
                        $storage[$cKey]["sum"]+=$row[$cValue[1]];
                    break;
                    case "min":
                        if($storage[$cKey]["min"]>$row[$cValue[1]])
                        {
                            $storage[$cKey]["min"] = $row[$cValue[1]];
                        }
                    break;
                    case "max":
                        if($storage[$cKey]["max"]<$row[$cValue[1]])
                        {
                            $storage[$cKey]["max"] = $row[$cValue[1]];
                        }
                    break;
                }
            }
        }

        foreach($this->data as &$row)
        {
            foreach($this->params as $cKey=>$cValue)
            {
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
                        if(!isset($storage[$cKey]["avg"]))
                        {
                            $storage[$cKey]["avg"] = $storage[$cKey]["sum"]/$storage[$cKey]["count"];
                        }
                        $row[$cKey] = $storage[$cKey]["avg"];
                    break;
                    case "acml":
                        if(!isset($storage[$cKey]["acml"]))
                        {
                            $storage[$cKey]["acml"] = 0;
                        }
                        $row[$cKey] = $storage[$cKey]["acml"]+$row[$cValue[1]];
                        $storage[$cKey]["acml"] = $row[$cKey];
                    break;
                }
            }
        }
        while($item = array_shift($this->data))
        {
            $this->next($item);
        }
    }
}