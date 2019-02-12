<?php
/**
 * This file contains class to handle data sorting
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
 * ->pipe(new ColumnsSort(array(
 *         "{name}"=>"desc",
 *         "{label}"=>"asc",
 *         //"{name}"=>function() {},
 *         //"{label}"=>function() {},
 * )))
 *  */
namespace koolreport\processes;

use \koolreport\core\Process;
use \koolreport\core\Utility;

/**
 * This file contains class to handle data sorting
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class ColumnsSort extends Process
{
    protected $nameSort;
    protected $labelSort;
    protected $fixedColumns;
    protected $sortedColumns;
    protected $meta;

    /**
     * Process initiation
     * 
     * @return null
     */
    public function onInit()
    {
        $this->nameSort = Utility::get($this->params, '{name}', null);
        $this->labelSort = Utility::get($this->params, '{label}', null);
        $this->fixedColumns = Utility::get($this->params, 'fixedColumns', null);
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
        $this->meta = $metaData;
        return $metaData;
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
        if (!isset($this->sortedColumns)) {
            $columns = array_keys($row);
            $columnsToSort = array();
            $fixedColumns = isset($this->fixedColumns) ? $this->fixedColumns : array();
            foreach ($columns as $i => $colName) {
                if (!array_key_exists($i, $fixedColumns) 
                    && !array_key_exists($colName, $fixedColumns)
                ) {
                    $columnsToSort[$i] = $colName;
                }
            }

            if (isset($this->nameSort)) {
                $sort = $this->nameSort;
                uasort(
                    $columnsToSort,
                    function ($a, $b) use ($sort) {
                        $cmp = 0;
                        if (is_string($sort)) {
                            $cmp = is_numeric($a) && is_numeric($b) ?
                            $a - $b : strcmp($a, $b);
                            $cmp = $sort === 'asc' ? $cmp : -$cmp;
                        } else if (is_callable($sort)) {
                            $cmp = $sort($a, $b);
                        }

                        return $cmp;
                    }
                );
            }
            if (isset($this->labelSort)) {
                $sort = $this->nameSort;
                $meta = $this->meta;
                uasort(
                    $columnsToSort,
                    function ($a, $b) use ($sort, $meta) {
                        $cmp = 0;
                        $a = isset($meta[$a]['label']) ? $meta[$a]['label'] : '';
                        $b = isset($meta[$b]['label']) ? $meta[$a]['label'] : '';
                        if (is_string($sort)) {
                            $cmp = is_numeric($a) && is_numeric($b) ?
                            $a - $b : strcmp($a, $b);
                            $cmp = $sort === 'asc' ? $cmp : -$cmp;
                        } else if (is_callable($sort)) {
                            $cmp = $sort($a, $b);
                        }

                        return $cmp;
                    }
                );
            }
            $sortedColumns = array();
            foreach ($fixedColumns as $i => $pos) {
                if (is_numeric($i)) {
                    $sortedColumns[$pos] = $columns[$i];
                } else {
                    $sortedColumns[$pos] = $i;
                }
            }

            $i = 0;
            foreach ($columnsToSort as $column) {
                while (isset($sortedColumns[$i])) {
                    $i++;
                }

                $sortedColumns[$i] = $column;
                $i++;
            }
            ksort($sortedColumns);
            $this->sortedColumns = $sortedColumns;
        }
        $newRow = array();
        foreach ($this->sortedColumns as $column) {
            $newRow[$column] = $row[$column];
        }

        $this->next($newRow);
    }
}
