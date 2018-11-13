<?php
/**
 * This file contains base class for processes.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\core;

class Process extends Node
{
	/**
	 * @var array $params Containing parameter settings of this process
	 */
	protected $params;
	
	public function __construct($params=null)
	{
		parent::__construct();
		$this->params = $params;
		$this->onInit();
	}

	/**
	 * This method will be called when process is initiated
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
	 */
	static function process($params)
	{
		$class = get_called_class();
		return new $class($params);
	}
}