<?php
/**
 * This file contains base class for processes.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
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
}