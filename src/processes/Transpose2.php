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
class Transpose2 extends Process
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
        $oldCKeys = array_keys($this->metaData['columns']);
        $cMetas = [
            'c0' => array("type" => "string"),
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
            if ($i === 0) {
                continue;
            }

            $newRow = ['c0' => $oldCKey];
            foreach ($this->data as $row) {
                $newRow[$row[$oldCKeys[0]]] = $row[$oldCKey];
            }

            $this->next($newRow);
        }
    }
}
