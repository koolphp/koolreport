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

class Transpose2 extends Process
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
        $oldCKeys = array_keys($this->metaData['columns']);
        $cMetas = [
            'c0' => array("type"=>"string"),
        ];
        foreach ($this->data as $row) {
            $cMetas[$row[$oldCKeys[0]]] = ['type' => 'unknown'];
        }
        // Utility::prettyPrint($cMetas); echo '<br>';
        $newMeta = array(
            "columns" => $cMetas,
        );
        $this->sendMeta($newMeta);

        foreach ($oldCKeys as $i => $oldCKey) {
            if ($i === 0) continue;
            $newRow = ['c0' => $oldCKey];
            foreach ($this->data as $row)
                $newRow[$row[$oldCKeys[0]]] = $row[$oldCKey];
            $this->next($newRow);
        }
    }
}