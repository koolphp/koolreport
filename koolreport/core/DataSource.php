<?php
/**
 * This file contains foundation class for all data sources. 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\core;

class DataSource extends Node
{
	protected $params;
	public function __construct($params)
	{
		parent::__construct();
		$this->params = $params;
		$this->onInit();
	}
	protected function onInit()
	{
		//Set up connection
	}	
	public function start()
	{
		//Start pushing data
	}
}
