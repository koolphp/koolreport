<?php
/**
 * This file contains class to map data value to another.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
->pipe(new Map(array(
'{value}' => function($row, $metaData, $index, $mapState) {
return $newRows;
return ['{rows}' => $newRows, '{state}' => $newMapState];
},
'{meta}' => function($metaData) {
return $newMeta;
},
'{end}' => function($count, $mapState) {
$avg = Util::get($mapState, 'sum', []);
foreach ($avg as $i => $v) {
$avg[$i] = is_numeric($v) ? $v / $count : 'Average';
}

$mapState['avg'] = $avg;
return $mapState;
},
)));
 *
 *
 */

namespace koolreport\processes;

use \koolreport\core\Process;
use \koolreport\core\Utility as Util;

/**
 * This file contains class to map data value to another.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class Map extends Process
{
    protected $metaSent = false;
    protected $metaData;
    protected $newMeta;
    protected $index = 0;
    protected $mapState = [];

    /**
     * Handle on initiation
     *
     * @return null
     */
    public function onInit()
    {
        $func = Util::get($this->params, '{init}', null);
        if (is_callable($func)) {
            $this->mapState = $func($data);
        }
    }

    /**
     * Handle on data recieved
     * 
     * @param array $metaData Metadata received
     * @param Node  $source   The source sending data
     * 
     * @return null 
     */
    public function receiveMeta($metaData, $source)
    {
        $this->streamingSource = $source;
        $this->newMeta = $this->metaData = $metaData;
        $func = Util::get($this->params, '{meta}', null);
        if (is_callable($func)) {
            $this->newMeta = $func($metaData);
        }
    }

    /**
     * Guess the type of value
     * 
     * @param mixed $value The value
     * 
     * @return string The type of value
     */
    protected function guessType($value)
    {
        $map = array(
            "float" => "number",
            "double" => "number",
            "int" => "number",
            "integer" => "number",
            "bool" => "number",
            "numeric" => "number",
            "string" => "string",
        );
        $type = strtolower(gettype($value));

        foreach ($map as $key => $value) {
            if (strpos($type, $key) !== false) {
                return $value;
            }
        }

        return "unknown";
    }
    /**
     * Convert to 2D array
     * 
     * @param array $arr The array
     * 
     * @return array The coverted array
     */
    protected function to2DArray($arr)
    {
        if (empty($arr) || !is_array($arr)) {
            return [];
        }
        if (count($arr) == count($arr, COUNT_RECURSIVE)) {
            return [$arr];
        }
        return $arr;
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
        $func = Util::get($this->params, '{value}', null);
        if (is_callable($func)) {
            $return = $func(
                $row,
                $this->metaData,
                $this->index, 
                $this->mapState
            );

            if (is_array($return) && (array_key_exists('{rows}', $return)
                ||array_key_exists('{state}', $return))
            ) {
                $newRows = Util::get($return, '{rows}', []);
                $this->mapState = Util::get($return, '{state}', $this->mapState);
            } else {
                $newRows = $return;
            }
            $newRows = $this->to2DArray($newRows);
            if (!$this->metaSent) {
                $colMetas = $this->newMeta['columns'];
                $newRow = Util::get($newRows, 0, []);
                foreach (array_keys($newRow) as $newCol) {
                    if (!isset($colMetas[$newCol])) {
                        $type = $this->guessType($newRow[$newCol]);
                        $colMetas[$newCol] = ['type' => $type];
                    }
                }
                $this->newMeta['columns'] = $colMetas;
                $this->sendMeta($this->newMeta);
                $this->metaSent = true;
            }
            foreach ($newRows as $row) {
                $this->next($row);
            }
        } else {
            if (!$this->metaSent) {
                $this->sendMeta($this->newMeta);
                $this->metaSent = true;
            }
            $this->next($row);
        }
        $this->index++;
    }

    /**
     * Handle on input end
     * 
     * @param Node $source The source sending
     * 
     * @return null
     */
    public function endInput($source)
    {
        if (!$this->metaSent) {
            $this->sendMeta($this->newMeta);
        }
        $func = Util::get($this->params, '{end}', null);
        if (is_callable($func)) {
            $newRows = $func($this->index, $this->mapState);
            $newRows = $this->to2DArray($newRows);
            foreach ($newRows as $row) {
                $this->next($row);
            }
        }
        parent::endInput($source);
    }
}
