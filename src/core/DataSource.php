<?php
/**
 * This file contains foundation class for all data sources. 
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

namespace koolreport\core;

/**
 * DataSource is foundation class for all datasources
 * 
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class DataSource extends Node
{
    /**
     * An associate array containing settings of a datasource
     * 
     * @var array $params An associate array containing settings of a datasource
     */
    protected $params;
    
    /**
     * Report's object containing this datasource
     * 
     * @var KoolReport $report Report's object containing this datasource
     */
    protected $report;

    /**
     * Constructor
     * 
     * Constructor of DataSource, it receive params for datasource and report object the datasource
     * belongs to.
     * 
     * @param array      $params The parameters to initiate a datasource 
     * @param KoolReport $report The report that datasource is attached to
     * 
     * @return null
     */
    public function __construct($params=null,$report=null)
    {
        parent::__construct();
        $this->params = $params;
        $this->report = $report;
        $this->onInit();
    }
    /**
     * This methods will be called when datasource object is initiated
     * 
     * When datasource object is initiated, this method will be called.
     * Normally this method will be overwritten by sub class
     * 
     * @return null
     */
    protected function onInit()
    {
        //Set up connection
    }

    /**
     * Start piping data
     * 
     * When this method is called, data will be pull from source and
     * start piping to series of processes until it reach datastore
     * which holds result of data.
     * 
     * @return null
     */
    public function start()
    {
        //Start pushing data
    }

    /**
     * Get report object
     * 
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
     * 
     * This method is called from the later node on the chain,
     * datasource will start piping data.
     * 
     * @return null
     */
    public function requestDataSending()
    {
        $this->start();
    }

    /**
     * Create data source
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
     * @param array      $params Parameters to create datasource
     * @param KoolReport $report Report that datasource is attached to
     * 
     * @return DataSource DataSource's object
     */
    static function create($params=null,$report=null)
    {
        $class = get_called_class();
        return new $class($params, $report);
    } 
}