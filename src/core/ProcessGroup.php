<?php
/**
 * This file contains foundation class for grouping processes into one.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
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

/**
 * Class for grouping processes into one.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class ProcessGroup extends Process
{
    /**
     * Settings of this Group Process
     * 
     * @var array $params Settings of this Group Process
     */
    protected $params;

    /**
     * The starting process
     * 
     * @var Process $startProcess The starting process
     */
    protected $startProcess;

    /**
     * The starting process
     * 
     * @var Process $endProcess The starting process
     */
    protected $endProcess;

    /**
     * Constructor
     * 
     * @param array $params The parameters for group process settings
     * 
     * @return null
     */    
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
     * 
     * @return null
     */
    public function setup()
    {
        //overwrite this function
    }
        
    /**
     * Get the starting process to start piping data
     * 
     * @return Process Return the starting process
     */
    protected function incoming()
    {
        return $this->startProcess;
    }


    /**
     * Get the end process to pipe data to
     * 
     * @return Process Return the end process
     */
    protected function outcoming()
    {
        return $this->endProcess;
    }
    
    /**
     * Receive the meta data from source
     * 
     * @param array   $metaData Metadata sent from source nodes
     * @param Process $source   The source process
     * 
     * @return null
     */
    public function receiveMeta($metaData,$source)
    {
        $this->streamingSource = $source;
        $this->metaData = $metaData;
        $this->startProcess->receiveMeta($metaData, $this);
    }

    /**
     * Event on input start
     * 
     * When receive input start signal the group process will 
     * forward to starting node
     * 
     * @return null
     */
    protected function onInputStart()
    {
        $this->startProcess->startInput($this);
    }

    /**
     * Event on input end
     * 
     * When input is ended, it forward to signal to starting process.
     * 
     * @return null
     */
    protected function onInputEnd()
    {
        $this->startProcess->endInput($this);
    }
    
    /**
     * Send meta data to the next nodes
     * 
     * @param array $metaData Meta data that will be sent
     * 
     * @return null
     */
    public function metaFromEndProcess($metaData)
    {
        $this->sendMeta($metaData);
    } 
        
    /**
     * Event on data input
     * 
     * The group process will forward data to the starting process
     * 
     * @param array $data The data input
     * 
     * @return null
     */
    public function onInput($data)
    {
        $this->startProcess->input($data, $this);
    }

    /**
     * On receving data from end process, it pipe to next nodes
     * 
     * @param array $data The associate data representing a data row
     * 
     * @return null
     */
    public function inputFromEndProcess($data)
    {
        $this->next($data);
    }
}