<?php
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