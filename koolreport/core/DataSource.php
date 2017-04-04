<?php
namespace koolreport\core;
use PDO;

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
