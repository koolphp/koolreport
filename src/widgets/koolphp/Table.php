<?php
/**
 * This file contains Table widget
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

// "columns"=>array(
//     "type",
//     "{others}"=>array(
//         "type"=>"number",
//         ""=>"" //Expression or function
//     )
// )

namespace koolreport\widgets\koolphp;

use \koolreport\core\DataStore;
use \koolreport\core\Utility;
use \koolreport\core\Widget;

/**
 * This file contains Table widget
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class Table extends Widget
{
    protected $name;
    protected $columns;
    protected $cssClass;
    protected $removeDuplicate;
    protected $excludedColumns;
    protected $formatFunction;

    protected $showFooter;
    protected $showHeader;

    protected $paging;

    protected $clientEvents;

    protected $headers;
    protected $responsive;

    protected $group;
    protected $sorting;

    protected $emptyValue;

    /**
     * Return the resource settings for table
     * 
     * @return array The resource settings of table widget
     */
    protected function resourceSettings()
    {
        return array(
            "library" => array("jQuery"),
            "folder" => "table",
            "js" => array("table.js"),
            "css" => array("table.css"),
        );
    }

    /**
     * Handle the initation
     * 
     * @return null
     */
    protected function onInit()
    {
        $this->useLanguage();
        $this->useDataSource();
        $this->useAutoName("ktable");
        $this->emptyValue = Utility::get($this->params, "emptyValue", 0);
        $this->clientEvents = Utility::get($this->params, "clientEvents");
        $this->columns = Utility::get($this->params, "columns", array());

        if ($this->dataStore == null) {
            $data = Utility::get($this->params, "data");
            if (is_array($data)) {
                if (count($data) > 0) {
                    $this->dataStore = new DataStore;
                    $this->dataStore->data($data);
                    $row = $data[0];
                    $meta = array("columns" => array());
                    foreach ($row as $cKey => $cValue) {
                        $meta["columns"][$cKey] = array(
                            "type" => Utility::guessType($cValue),
                        );
                    }
                    $this->dataStore->meta($meta);
                } else {
                    $this->dataStore = new DataStore;
                    $this->dataStore->data(array());
                    $metaColumns = array();
                    foreach ($this->columns as $cKey => $cValue) {
                        if (gettype($cValue) == "array") {
                            $metaColumns[$cKey] = $cValue;
                        } else {
                            $metaColumns[$cValue] = array();
                        }
                    }
                    $this->dataStore->meta(array("columns" => $metaColumns));
                }
            }
            if ($this->dataStore == null) {
                throw new \Exception("dataSource is required for Table");
                return;
            }
        }

        if ($this->dataStore->countData() == 0 && count($this->dataStore->meta()["columns"]) == 0) {
            $meta = array("columns" => array());
            foreach ($this->columns as $cKey => $cValue) {
                if (gettype($cValue) == "array") {
                    $meta["columns"][$cKey] = $cValue;
                } else {
                    $meta["columns"][$cValue] = array();
                }
            }
            $this->dataStore->meta($meta);
        }

        $this->removeDuplicate = Utility::get($this->params, "removeDuplicate", array());
        $this->cssClass = Utility::get($this->params, "cssClass", array());
        $this->excludedColumns = Utility::get($this->params, "excludedColumns", array());

        $this->showFooter = Utility::get($this->params, "showFooter");
        $this->showHeader = Utility::get($this->params, "showHeader", true);

        $this->paging = Utility::get($this->params, "paging");
        if ($this->paging !== null) {
            $this->paging = array(
                "pageSize" => Utility::get($this->paging, "pageSize", 10),
                "pageIndex" => Utility::get($this->paging, "pageIndex", 0),
                "align" => Utility::get($this->paging, "align", "left"),
            );
            $this->paging["itemCount"] = $this->dataStore->countData();
            $this->paging["pageCount"] = ceil($this->paging["itemCount"] / $this->paging["pageSize"]);
        }

        //Header Group
        $this->headers = Utility::get($this->params, "headers", array());
        $this->responsive = Utility::get($this->params, "responsive", false);
        $this->sorting = Utility::get($this->params, "sorting", array());

        $group = Utility::get($this->params, "grouping");
        $this->group = array();
        if ($group) {
            foreach ($group as $cKey => $cValue) {
                if (gettype($cValue) == "array") {
                    $this->group[$cKey] = $cValue;
                } else if (gettype($cValue) == "string") {
                    $this->group[$cValue] = array(
                        "top" => "<strong>{" . $cValue . "}</strong>",
                    );
                }
            }
        }
    }

    /**
     * Format value
     * 
     * @param mixed $value  The value needed to be formatted
     * @param mixed $format The format settings
     * @param mixed $row    The row that value belongs to
     * 
     * @return string Formatted value
     */
    public static function formatValue($value, $format, $row = null)
    {
        $formatValue = Utility::get($format, "formatValue", null);

        if (is_string($formatValue)) {
            eval('$fv="' . str_replace('@value', '$value', $formatValue) . '";');
            return $fv;
        } else if (is_callable($formatValue)) {
            return $formatValue($value, $row);
        } else {
            return Utility::format($value, $format);
        }
    }

    /**
     * Group the level
     * 
     * @param array $meta           The metadata
     * @param array $groupModel     The group model
     * @param array $store          The store
     * @param array $result         The previous result
     * @param array $level          The level
     * @param array $start          The starting position
     * @param array $previousParams The previous parameters
     * 
     * @return array Result
     */
    static function groupLevel(
        $meta, 
        $groupModel, 
        $store, 
        &$result, 
        $level = 0,
        $start = 0, 
        $previousParams = array()
    ) {
        $keys = array_keys($groupModel);
        $store->breakGroup(
            $keys[$level],
            function ($store, $localStart) use ($meta, $groupModel, &$result, $keys, $level, $start, $previousParams) {
                $by = $keys[$level];
                $agroup = array_merge(
                    $previousParams,
                    array(
                        "{" . $by . "}" => $store->get(0, $by),
                        "{count}" => $store->count(),
                    )
                );
                $previousParams["{" . $by . "}"] = $agroup["{" . $by . "}"];
                $calculate = Utility::get($groupModel[$by], "calculate", array());
                $css = Utility::get($groupModel[$by], "css");
                $cssClass = Utility::get($groupModel[$by], "cssClass");
                foreach ($calculate as $paramName => $def) {
                    if (is_array($def)) {
                        $method = strtolower($def[0]);
                        if (in_array($method, array("sum", "count", "min", "max", "mode"))) {
                            $agroup[$paramName] = Table::formatValue($store->$method($def[1]), $meta["columns"][$def[1]]);
                        }

                    } else if (is_callable($def)) {
                        $agroup[$paramName] = $def($store);
                    }
                }
                $startTemplate = Utility::get($groupModel[$by], "top");
                $endTemplate = Utility::get($groupModel[$by], "bottom");

                if ($startTemplate) {
                    if (!isset($result[$start + $localStart])) {
                        $result[$start + $localStart] = array();
                    }
                    $item = array(
                        $start + $localStart,
                        $start + $localStart + $agroup["{count}"],
                        is_string($startTemplate) ? Utility::strReplace($startTemplate, $agroup) :
                        (is_callable($startTemplate) ? $startTemplate($agroup) : $startTemplate),
                        null, null,
                    );
                    if ($css) {
                        $item[3] = gettype($css) == "string" ? $css : $css($agroup);
                    }
                    if ($cssClass) {
                        $item[4] = gettype($cssClass) == "string" ? $cssClass : $cssClass($agroup);
                    }
                    array_push($result[$start + $localStart], $item);
                }
                if ($endTemplate) {
                    if (!isset($result[$start + $localStart + $agroup["{count}"]])) {
                        $result[$start + $localStart + $agroup["{count}"]] = array();
                    }

                    $item = array(
                        $start + $localStart,
                        $start + $localStart + $agroup["{count}"],
                        is_string($endTemplate) ? Utility::strReplace($endTemplate, $agroup) :
                        (is_callable($endTemplate) ? $endTemplate($agroup) : $endTemplate),
                        null, null,
                    );
                    if ($css) {
                        $item[3] = gettype($css) == "string" ? $css : $css($agroup);
                    }
                    if ($cssClass) {
                        $item[4] = gettype($cssClass) == "string" ? $cssClass : $cssClass($agroup);
                    }
                    array_unshift($result[$start + $localStart + $agroup["{count}"]], $item);
                }
                if ($level < count($keys) - 1) {
                    Table::groupLevel($meta, $groupModel, $store, $result, $level + 1, $start + $localStart, $previousParams);
                }
            }
        );
    }

    /**
     * Generate groups for table grouping
     * 
     * @param array $meta The meta data of table
     *
     * @return array List of results
     */
    protected function generateGroups($meta)
    {
        if ($this->group) {
            $result = array();
            $sorts = array();
            foreach ($this->group as $by => $settings) {
                $sorts[$by] = Utility::get($settings, "sort", "asc");
            }
            $sorts = array_merge($sorts, $this->sorting);
            $this->dataStore->sort($sorts);
            Table::groupLevel($meta, $this->group, $this->dataStore, $result);
            return $result;
        }
        return false;
    }

    /**
     * Echo the row group content in html
     * 
     * @param array   $groups  The groups
     * @param integer $index   The index of data rows
     * @param integer $colspan The number of colspan
     * 
     * @return null
     */
    protected function renderRowGroup($groups, $index, $colspan)
    {
        if ($groups && isset($groups[$index])) {
            foreach ($groups[$index] as $grow) {
                if ($this->paging) {
                    $grow[3] = "display:none;" . $grow[3];
                }
                echo "<tr from='$grow[0]' to='$grow[1]' class='row-group" . ($grow[4] ? " $grow[4]" : "") . "' " . ($grow[3] ? "style='$grow[3]'" : "") . ">";
                if (strpos($grow[2], "<td") === 0) {
                    echo $grow[2];
                } else {
                    echo "<td colspan='$colspan'>$grow[2]</td>";
                }
                echo "</tr>";
            }
        }
    }

    /**
     * Handle on widget rendering
     * 
     * @return null
     */
    public function onRender()
    {

        $meta = $this->dataStore->meta();
        $showColumnKeys = array();

        if ($this->columns == array()) {
            if ($row = $this->dataStore[0]) {
                $showColumnKeys = array_keys($row);
            } else if (count($meta["columns"]) > 0) {
                $showColumnKeys = array_keys($meta["columns"]);
            }
        } else {
            foreach ($this->columns as $cKey => $cValue) {

                if ($cKey === "{others}") {
                    $allKeys = array_keys($this->dataStore[0]);
                    foreach ($allKeys as $k) {
                        if (!in_array($k, $showColumnKeys)) {
                            $meta["columns"][$k] = array_merge($meta["columns"][$k], $cValue);
                            array_push($showColumnKeys, $k);
                        }
                    }
                } else {
                    if (gettype($cValue) == "array") {
                        if ($cKey === "#") {
                            $meta["columns"][$cKey] = array(
                                "type" => "number",
                                "label" => "#",
                                "start" => 1,
                            );
                        }

                        $meta["columns"][$cKey] = array_merge($meta["columns"][$cKey], $cValue);
                        if (!in_array($cKey, $showColumnKeys)) {
                            array_push($showColumnKeys, $cKey);
                        }
                    } else {
                        if ($cValue === "#") {
                            $meta["columns"][$cValue] = array(
                                "type" => "number",
                                "label" => "#",
                                "start" => 1,
                            );
                        }
                        if (!in_array($cValue, $showColumnKeys)) {
                            array_push($showColumnKeys, $cValue);
                        }
                    }

                }
            }
        }

        $cleanColumnKeys = array();
        foreach ($showColumnKeys as $key) {
            if (!in_array($key, $this->excludedColumns)) {
                array_push($cleanColumnKeys, $key);
            }
        }
        $showColumnKeys = $cleanColumnKeys;

        if (count($this->group) === 0 && count($this->sorting)>0) {
            $this->dataStore->sort($this->sorting);
        }

        //Prepare data
        $this->template(
            "Table",
            array(
                "showColumnKeys" => $showColumnKeys,
                "meta" => $meta,
            )
        );
    }
}
