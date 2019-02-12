<?php
/**
 * This file contains process to limit the rows return from datasource
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/* Usage
 * ->pipe(new Limit(array(10,5)))
 *
 * Limit number of row to 10 and starting from row 5
 *
 */
namespace koolreport\processes;

use \koolreport\core\Process;
use \koolreport\core\Utility;

/**
 * This file contains process to limit the rows return from datasource
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class Limit extends Process
{
    protected $index = -1;
    protected $limit;
    protected $offset;

    /**
     * Handle on initiation
     *
     * @return null
     */
    protected function onInit()
    {
        $this->limit = Utility::get($this->params, 0, 10);
        $this->offset = Utility::get($this->params, 1, 0);
    }

    /**
     * Handle on input start
     * 
     * @return null
     */
    protected function onStartInput()
    {
        $this->index = -1;
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
        $this->index++;
        if ($this->index >= $this->offset && $this->index < $this->offset + $this->limit) {
            $this->next($data);
        }
    }
}
