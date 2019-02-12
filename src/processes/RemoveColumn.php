<?php
/**
 * This file contains class to remove some of columns.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
 * ->pipe(new RemoveColumn(array(
 *         "name","first_name"
 * )))
 * Create a new column with new name with same value
 *
 */
namespace koolreport\processes;

use \koolreport\core\Process;

/**
 * This file contains class to remove some of columns.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class RemoveColumn extends Process
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
        foreach ($this->params as $column) {
            unset($metaData["columns"][$column]);
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
        //Process data here
        foreach ($this->params as $column) {
            unset($data[$column]);
        }
        $this->next($data);
    }
}
