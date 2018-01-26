<?php
/**
 * This file contains class to transpose column and row of table
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/*

->pipe(new Transpose())

*/

namespace koolreport\processes;
use \koolreport\core\Utility;
use \koolreport\core\Process;

class Transpose extends Process
{
    protected $data;

    protected function onInit()
    {
        $this->data = array();
    }

	public function receiveMeta($metaData,$source)
	{
		$this->streamingSource = $source;
		$this->metaData = $metaData;
	}

    protected function onInput($row)
    {
        array_push($this->data,$row);
    }

    protected function onInputEnd()
    {
        //Send meta
        $countRow = count($this->data);
        $newMeta = array(
            "columns"=>array(),
        );
        for($i=0;$i<=$countRow;$i++)
        {
            $newMeta["columns"]["c$i"] = array("type"=>"unknown");
        }
        $newMeta["columns"]["c0"]["type"]="string";
        $this->sendMeta($newMeta);


        if($countRow>0)
        {
            $keys = array_keys($this->data[0]);
        }
        else
        {
            $keys = array_keys($this->metaData["columns"]);
        }

        //Send each rows
        foreach($keys as $cKey)
        {
            $row = array("c0"=>isset($this->metaData["columns"][$cKey]["label"])?$this->metaData["columns"][$cKey]["label"]:$cKey);   
            for($i=0;$i<$countRow;$i++)
            {
                $row["c".($i+1)] = $this->data[$i][$cKey];
                unset($this->data[$i][$cKey]);
            }
            $this->next($row);
        }
    }
}