<?php
/**
 * This file contain class that allows only some defined columns to go through.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
 * ->pipe(new OnlyColumn(array(
 *         "id","name","address"
 * )))
 * Only have those columns are passed.
 *
 */
namespace koolreport\processes;

use \koolreport\core\Process;

/**
 * This file contain class that allows only some defined columns to go through.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class OnlyColumn extends Process
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
        $meta = $metaData;
        $meta["columns"] = array();
        foreach ($this->params as $colname) {
            if (isset($metaData["columns"][$colname])) {
                $meta["columns"][$colname] = $metaData["columns"][$colname];
            }
        }
        return $meta;
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
        $ndata = array();
        foreach ($this->params as $colname) {
            if (isset($data[$colname])) {
                $ndata[$colname] = $data[$colname];
            }
        }

        $this->next($ndata);
    }
}
