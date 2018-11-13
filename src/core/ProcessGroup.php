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
	/**
	 * @var array $params Settings of this Group Process
	 */
	protected $params;

	/**
	 * @var Process $startProcess The starting process
	 */
	protected $startProcess;

	/**
	 * @var Process $endProcess The starting process
	 */
	protected $endProcess;

	public function __construct($params=null)
	{
		parent::__construct($params);
		$this->startProcess = new Node();
		$this->endProcess = new ProcessGroupEnd($this);
		$this->setup();		
	}
	
	/**
	 * Setup the piping processes
	 * 
	 * This method will be overwritten by descendent to provide the flow of processes
	 */
	public function setup()
	{
		//overwrite this function
	}
		
	/**
	 * Get the starting process to start piping data
	 */
	protected function incoming()
	{
		return $this->startProcess;
	}	
	/**
	 * Get the end process to pipe data to
	 */
	protected function outcoming()
	{
		return $this->endProcess;
	}
	
	/**
	 * Receive the meta data from source
	 * 
	 * @param array $metaData Metadata sent from source nodes
	 */
	public function receiveMeta($metaData,$source)
	{
		$this->streamingSource = $source;
		$this->metaData = $metaData;
		$this->startProcess->receiveMeta($metaData,$this);
	}

	/**
	 * Event on input start
	 * 
	 * When receive input start signal the group process will forward to starting node
	 */
	protected function onInputStart()
	{
		$this->startProcess->startInput($this);
	}

	/**
	 * Event on input end
	 * 
	 * When input is ended, it forward to signal to starting process.
	 */
	protected function onInputEnd()
	{
		$this->startProcess->endInput($this);
	}
	
	/**
	 * Send meta data to the next nodes
	 * 
	 * @param array $metaData Meta data that will be sent
	 */
	public function metaFromEndProcess($metaData)
	{
		$this->sendMeta($metaData);
	} 
		
	/**
	 * Event on data input
	 * 
	 * The group process will forward data to the starting process
	 */
	public function onInput($data)
	{
		$this->startProcess->input($data,$this);
	}

	/**
	 * On receving data from end process, it pipe to next nodes
	 * 
	 * @param array $data The associate data representing a data row
	 */
	public function inputFromEndProcess($data)
	{
		$this->next($data);
	}
}