<?php
/**
 * This file contains base class for processes.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

namespace koolreport\core;

/**
 * Base class for process
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class Process extends Node
{
    /**
     * Containing parameter settings of this process
     * 
     * @var array $params Containing parameter settings of this process
     */
    protected $params;
    
    /**
     * Constructor
     * 
     * @param array $params The parameters for process settings
     * 
     * @return null
     */    
    public function __construct($params=null)
    {
        parent::__construct();
        $this->params = $params;
        $this->onInit();
    }

    /**
     * This method will be called when process is initiated
     * 
     * @return null
     */
    protected function onInit()
    {
        //The descendant will override this function
    }

    /**
     * Create a new process object
     * 
     * Examples
     * 
     * ->pipe(Group::process(["by"=>"time"]))
     * 
     * @param array $params The parameter to initiate this process
     * 
     * @return null
     */
    static function process($params)
    {
        $class = get_called_class();
        return new $class($params);
    }
}