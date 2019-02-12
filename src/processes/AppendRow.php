<?php
/**
 * This file contains process to append row/rows to data stream
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
 * ->pipe(new AppendRow(
 * 		array("name"=>"John")
 * ))
 * 
 * or multiple row
 * 
 * ->pipe(new AppendRow(array(
 * 		array("name"=>"John"),
 * 		array("name"=>"Marry"),
 * )))
 */
namespace koolreport\processes;
use \koolreport\core\Process;
use \koolreport\core\Utility;

/**
 * This file contains process to append row/rows to data stream
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class AppendRow extends Process
{
    /**
     * Handle on input end
     * 
     * @return null
     */
    protected function onInputEnd()
    {
        $data = array();
        if (Utility::isAssoc($this->params)) {
            $data = array($this->params);
        } else {
            $data = $this->params;
        }
        foreach ($data as $row) {
            $this->next($row);
        }
    }
}