<?php
/**
 * This file contains foundation class for all data sources. 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\core;

class DataSource extends Node
{
	/**
	 * @var array $params An associate array containing settings of a datasource
	 *
	 */
	protected $params;
	
	/**
	 * @var KoolReport $report Report's object containing this datasource
	 */
	protected $report;
	public function __construct($params=null,$report=null)
	{
		parent::__construct();
		$this->params = $params;
		$this->report = $report;
		$this->onInit();
	}
	/**
	 * This methods will be called when datasource object is initiated
	 */
	protected function onInit()
	{
		//Set up connection
	}	

	/**
	 * When this method is called, data will be pull from source and
	 * start piping to series of processes until it reach datastore
	 * which holds result of data.
	 */
	public function start()
	{
		//Start pushing data
	}

	/**
	 * Return the report object that contains this datasource
	 * 
	 * @return KoolReport Report object that contains this datasource
	 */
	public function getReport()
	{
		return $this->report;
	}

	/**
	 * Start piping data
	 */
	public function requestDataSending()
	{
		$this->start();
	}

	/**
	 * When call it will create a datasource object with parameter
	 * 
	 * It is simply another way of creating datasource object.
	 * For example we can create PdoDataSource like this:
	 * $ds = PdoDataSource:create(array(
     *   "connectionString"=>"mysql:host=localhost;dbname=automaker",
     *   "username"=>"root",
     *   "password"=>"",
     *   "charset"=>"utf8"
	 * ));
	 * 
	 * @return DataSource DataSource's object
	 */
	static function create($params=null,$report=null)
	{
		$class = get_called_class();
		return new $class($params,$report);
	} 
}
