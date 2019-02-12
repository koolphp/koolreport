<?php
/**
 * This file contains process to copy a column including data and meta data.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
 * ->pipe(new CopyColumn(array(
 *         "amountCopy"=>"amount",
 *         "timeCopy"=>"time",
 * )))
 * Create a new column with new name with same value
 *
 */
namespace koolreport\processes;

use \koolreport\core\Process;

/**
 * This file contains process to copy a column including data and meta data.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class CopyColumn extends Process
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
        foreach ($this->params as $copy => $original) {
            $metaData["columns"][$copy] = $metaData["columns"][$original];
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
    public function onInput($data)
    {
        //Process data here
        foreach ($this->params as $copy => $original) {
            $data[$copy] = $data[$original];
        }
        $this->next($data);
    }
}
