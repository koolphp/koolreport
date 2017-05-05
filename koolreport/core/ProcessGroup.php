<?php
/**
 * This file contains foundation class for grouping processes into one.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */


/* Usage:
 * 
 * class MyGroup extends ProcessGroup
 * {
 * 		public function setup()
 * 		{
 * 			$this	->incomming()
 * 					->pipe(new Process())
 * 					->pipe($this->outcomming());
 * 
 * 		}
 * 
 * 
 * }
 */
namespace koolreport\core;


class ProcessGroupEnd extends Process
{	
	public function onInput($data)
	{
		$this->params->inputFromEndProcess($data);
	}
	protected function receiveMeta($metaData)
	{
		$this->params->metaFromEndProcess($metaData);
	}
}

class ProcessGroup extends Process
{
	protected $params;
	protected $startProcess;
	protected $endProcess;
	public function __construct($params)
	{
		parent::__construct();
		$this->internalPoints = array();
		$this->params = $params;
		$this->startProcess = new Node();
		$this->endProcess = new ProcessGroupEnd($this);
		$this->setup($this->params);		
	}
	
	public function setup($params)
	{
	}
		
	protected function incomming()
	{
		return $this->startProcess;
	}	
	protected function outcoming()
	{
		return $this->endProcess;
	}
	
	protected function receiveMeta($metaData)
	{
		$this->startProcess->receiveMeta($metaData);
	}
	
	public function metaFromEndProcess($metaData)
	{
		$this->metaData = $metaData;
		$this->sendMeta($metaData);
	} 
		
	public function onInput($data)
	{
		$this->startProcess->input($data);
	}
	public function inputFromEndProcess($data)
	{
		$this->next($data);
	}
}