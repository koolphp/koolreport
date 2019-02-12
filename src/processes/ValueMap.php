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
 * ->pipe(new ValueMap(array(
 *         "columnName"=>array(
 *             "1"=>"One",
 *             "2"=>"Two",
 *      "{func}"=>function($value) {
 *        return 'prefix' . $value;
 *      },
 *             "{meta}"=>array(
 *                 "type"=>"string"
 *             )
 *         ),
 * )))
->pipe(new ValueMap(function($value, $columnName, $columnMeta, $columnPos){
return $newValue;
}))
 *
 *
 */
namespace koolreport\processes;

use \koolreport\core\Process;
use \koolreport\core\Utility;

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
class ValueMap extends Process
{
    protected $mapFuncs = array();

    /**
     * Handle on initation
     *
     * @return null
     */
    protected function onInit()
    {
        if (is_array($this->params)) {
            foreach ($this->params as $cName => $cMap) {
                $cFunc = Utility::get($cMap, "{func}");
                if (is_callable($cFunc)) {
                    $this->mapFuncs[$cName] = $cFunc;
                }

            }
        }

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
        if (is_array($this->params)) {
            foreach ($this->params as $cName => $cMap) {
                if (is_array($cMap)) {
                    $cMeta = Utility::get($cMap, "{meta}");
                    if ($cMeta) {
                        $override = Utility::get($cMeta, "{override}", false);
                        if (isset($cMeta["{override}"])) {
                            unset($cMeta["{override}"]);
                        }
                        if ($override) {
                            $metaData["columns"][$cName] = $cMeta;
                        } else {
                            $metaData["columns"][$cName] = array_merge($metaData["columns"][$cName], $cMeta);
                        }
                    }
                }
            }
        }

        return $metaData;
    }

    /**
     * Handle on data input
     * 
     * @param array $row The data row
     * 
     * @return null
     */
    protected function onInput($row)
    {
        if (is_array($this->params)) {
            foreach ($this->params as $cName => $cMap) {
                if (isset($this->mapFuncs[$cName])) {
                    $row[$cName] = $this->mapFuncs[$cName]($row[$cName]);
                } else if (is_array($cMap)) {
                    $row[$cName] = Utility::get($cMap, $row[$cName], $row[$cName]);
                }

            }
        } else if (is_callable($this->params)) {
            $func = $this->params;
            $pos = 0;
            foreach ($row as $cName => $value) {
                $row[$cName] = $func($value, $cName, $this->metaData['columns'][$cName], $pos);
                $pos++;
            }
        }
        $this->next($row);
    }
}
