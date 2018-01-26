<?php
/**
 * This file contains foundation class for grouping processes into one.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */


/* Usage:
 * 
 * class MyGroup extends ProcessGroup
 * {
 * 		public function setup()
 * 		{
 * 			$this	->incoming()
 * 					->pipe(new Process())
 * 					->pipe($this->outcoming());
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
	public function receiveMeta($metaData,$source)
	{
		$this->params->metaFromEndProcess($metaData);
	}
}

class ProcessGroup extends Process
{
	protected $params;
	protected $startProcess;
	protected $endProcess;
	public function __construct($params=null)
	{
		parent::__construct($params);
		$this->startProcess = new Node();
		$this->endProcess = new ProcessGroupEnd($this);
		$this->setup();		
	}
	
	public function setup()
	{
		//overwrite this function
	}
		
	protected function incoming()
	{
		return $this->startProcess;
	}	
	protected function outcoming()
	{
		return $this->endProcess;
	}
	
	public function receiveMeta($metaData,$source)
	{
		$this->streamingSource = $source;
		$this->metaData = $metaData;
		$this->startProcess->receiveMeta($metaData,$this);
	}

	protected function onInputStart()
	{
		$this->startProcess->startInput($this);
	}

	protected function onInputEnd()
	{
		$this->startProcess->endInput($this);
	}
	
	public function metaFromEndProcess($metaData)
	{
		$this->sendMeta($metaData);
	} 
		
	public function onInput($data)
	{
		$this->startProcess->input($data,$this);
	}
	public function inputFromEndProcess($data)
	{
		$this->next($data);
	}
}