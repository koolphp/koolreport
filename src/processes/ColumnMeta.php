<?php
/**
 * This file contain process to set meta data for column.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
 * ->pipe(new ColumnMeta(array(
 *         "amount"=>array(
 *             "name"=>"sale_amount"
 *             "type"=>"number",
 *             "label"=>"Amount",
 *         ),
 *         "{override}"=>false
 * )))
 * ->pipe(new ColumnMeta(function($columnName, $columnMeta, $columnPos){
return $newMeta;
}))
 * Create a new column with new name with same value
 * the {override} is directive to guide ColumnMeta to overide column or just add more information to the meta.
 */
namespace koolreport\processes;

use \koolreport\core\Process;
use \koolreport\core\Utility;

/**
 * This file contain process to set meta data for column.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class ColumnMeta extends Process
{

    protected $override;
    protected $nameMap = array();

    /**
     * Handle on initiation
     * 
     * @return null
     */
    protected function onInit()
    {
        if (is_array($this->params)) {
            $this->override = Utility::get($this->params, "{override}", false);
        }

    }

    /**
     * Handle on meta received
     * 
     * @param array $metaData Meta received
     * 
     * @return array New meta data
     */
    protected function onMetaReceived($metaData)
    {
        if (is_array($this->params)) {
            foreach ($this->params as $columnName => $columnInfo) {
                if (isset($metaData["columns"][$columnName])) {
                    $newColumnName = Utility::get($columnInfo, "name");
                    $currentColumnInfo = $metaData["columns"][$columnName];
                    if ($newColumnName) {
                        unset($columnInfo["name"]);
                        unset($metaData["columns"][$columnName]);
                    }

                    if ($this->override) {
                        $metaData["columns"][($newColumnName) ? $newColumnName : $columnName] = $columnInfo;
                    } else {
                        $metaData["columns"][($newColumnName) ? $newColumnName : $columnName] = array_merge($currentColumnInfo, $columnInfo);
                    }
                }
            }
        } else if (is_callable($this->params)) {
            $func = $this->params;
            $columns = $metaData['columns'];
            $pos = 0;
            foreach ($columns as $c => $cMeta) {
                $newMeta = $func($c, $cMeta, $pos);
                if (isset($newMeta['name'])) {
                    $newName = $newMeta['name'];
                    $this->nameMap[$c] = $newName;
                    // unset($columns[$c]);
                    unset($newMeta['name']);
                    $columns[$newName] = $newMeta;
                } else {
                    $columns[$c] = $newMeta;
                }
                $pos++;
            }
            $metaData['columns'] = $columns;
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
        if (is_array($this->params)) {
            foreach ($this->params as $columnName => $columnInfo) {
                if (isset($columnInfo["name"])) {
                    $columnValue = $data[$columnName];
                    unset($data[$columnName]);
                    $data[$columnInfo["name"]] = $columnValue;
                }
            }
        } else {
            foreach ($this->nameMap as $oldName => $newName) {
                $value = $data[$oldName];
                unset($data[$oldName]);
                $data[$newName] = $value;
            }
        }
        $this->next($data);
    }
}
