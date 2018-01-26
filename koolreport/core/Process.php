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
	protected $params;
	
	public function __construct($params=null)
	{
		parent::__construct();
		$this->params = $params;
		$this->onInit();
	}
	protected function onInit()
	{
		//The descendant will override this function
	}

	static function process($params)
	{
		$class = get_called_class();
		return new $class($params);
	}
}