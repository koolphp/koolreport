<?php
/**
 * This file contains process to rename a column including data and meta data.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
 * ->pipe(new ColumnRename(array(
 *         "amount"=>"sale_amount",
 *         "time"=>"timing",
 * )))
 *
 */
namespace koolreport\processes;

use \koolreport\core\Process;

/**
 * This file contains process to rename a column including data and meta data.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class ColumnRename extends Process
{
    
    /**
     * Handle on meta received
     * 
     * @param array $metaData The meta data
     * 
     * @return array New meta data
     */
    protected function onMetaReceived($metaData)
    {
        $keys = array_keys($metaData["columns"]);
        $values = array_values($metaData["columns"]);

        for ($i = 0; $i < count($keys); $i++) {
            if (isset($this->params[$keys[$i]])) {
                $keys[$i] = $this->params[$keys[$i]];
            }
        }
        $metaData["columns"] = array_combine($keys, $values);
        return $metaData;
    }

    /**
     * Handle on data input
     *
     * @param array $row The input data row
     *
     * @return null
     */
    public function onInput($row)
    {
        $keys = array_keys($row);
        $values = array_values($row);
        for ($i = 0; $i < count($keys); $i++) {
            if (isset($this->params[$keys[$i]])) {
                $keys[$i] = $this->params[$keys[$i]];
            }
        }
        $this->next(array_combine($keys, $values));
    }
}
