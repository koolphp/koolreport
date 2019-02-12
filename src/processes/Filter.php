<?php
/**
 * This file contains process to filter rows based on condition.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
->pipe(new Filter(array(
'or',
array('age','>',4),
array('name','contain','Tuan'),
'and',
array('time','<=','2010-12-31')),
))
 */
namespace koolreport\processes;

use \koolreport\core\Process;

/**
 * This file contains process to filter rows based on condition.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class Filter extends Process
{
    protected $conditions;
    protected $logicalOperator;

    /**
     * Process initiation
     * 
     * @return null
     */
    public function onInit()
    {
        $this->filters = isset($this->params) ? $this->params : array();
    }

    /**
     * Return true if result is filtered
     * 
     * @param array  $condition Condition
     * @param mixed  $value     The value
     * @param string $type      The type of value
     * 
     * @return bool Whether result is filtered
     */
    public function isFiltered($condition, $value, $type)
    {
        $isFiltered = true;
        switch ($condition[1]) {
        case '=':
        case '==':
        case 'equal':
            if ($type === 'string' && is_string($value) && is_string($condition[2])) {
                $isFiltered = strcmp($value, $condition[2]) == 0;
            } else {
                $isFiltered = $value == $condition[2];
            }

            break;
        case '!=':
        case 'notEqual':
            if ($type === 'string' && is_string($value) && is_string($condition[2])) {
                $isFiltered = strcmp($value, $condition[2]) != 0;
            } else {
                $isFiltered = $value != $condition[2];
            }

            break;
        case '>':
        case 'gt':
            if ($type === 'string') {
                $isFiltered = strcmp($value, $condition[2]) > 0;
            } else {
                $isFiltered = $value > $condition[2];
            }

            break;
        case '>=':
            if ($type === 'string') {
                $isFiltered = strcmp($value, $condition[2]) >= 0;
            } else {
                $isFiltered = $value >= $condition[2];
            }

            break;
        case '<':
        case 'lt':
            if ($type === 'string') {
                $isFiltered = strcmp($value, $condition[2]) < 0;
            } else {
                $isFiltered = $value < $condition[2];
            }

            break;
        case '<=':
            if ($type === 'string') {
                $isFiltered = strcmp($value, $condition[2]) <= 0;
            } else {
                $isFiltered = $value <= $condition[2];
            }

            break;
        case 'contain':
        case 'contains':
            $isFiltered = strpos(strtolower($value), strtolower($condition[2])) !== false;
            break;
        case 'notContain':
        case 'notContains':
            $isFiltered = strpos(strtolower($value), strtolower($condition[2])) === false;
            break;
        case 'startWith':
        case 'startsWith':
            $isFiltered = strpos(strtolower($value), strtolower($condition[2])) === 0;
            break;
        case 'notStartWith':
        case 'notStartsWith':
            $isFiltered = strpos(strtolower($value), strtolower($condition[2])) !== 0;
            break;
        case 'endWith':
        case 'endsWith':
            $isFiltered = strpos(strrev(strtolower($value)), strrev(strtolower($condition[2]))) === 0;
            break;
        case 'notEndWith':
        case 'notEndsWith':
            $isFiltered = strpos(strrev(strtolower($value)), strrev(strtolower($condition[2]))) !== 0;
            break;
        case 'between':
            $isFiltered = $value > $condition[2] && $value < $condition[3];
            break;
        case 'notBetween':
            $isFiltered = !($value > $condition[2] && $value < $condition[3]);
            break;
        case "in":
            if (!is_array($condition[2])) {
                $condition[2] = array($condition[2]);
            }

            $isFiltered = in_array($value, $condition[2]);
            break;
        case "notIn":
            if (!is_array($condition[2])) {
                $condition[2] = array($condition[2]);
            }

            $isFiltered = !in_array($value, $condition[2]);
            break;
        default:
            break;
        }
        return $isFiltered;
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
        $columnsMeta = $this->metaData['columns'];
        $filters = $this->filters;
        $logicalOperator = 'and';
        $isFiltered = true;
        foreach ($filters as $i => $filter) {
            if (is_array($filter)) {
                $field = $filter[0];
                $type = $columnsMeta[$field]['type'];
                if (!isset($data[$field])) {
                    continue;
                }

                $filterResult = $this->isFiltered($filter, $data[$field], $type);
                if ($logicalOperator === 'and') {
                    $isFiltered = $isFiltered && $filterResult;
                }
                if ($logicalOperator === 'or') {
                    $isFiltered = $isFiltered || $filterResult;
                }
            } else if ($filter === 'and' || $filter === 'or') {
                $logicalOperator = $filter;
                if ($filter === 'or' && $i === 0) {
                    $isFiltered = false;
                }

            }
        }
        if ($isFiltered) {
            $this->next($data);
        }
    }
}
