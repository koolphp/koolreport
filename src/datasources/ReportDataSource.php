<?php
/**
 * This file contains class to pull data from other reports.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

namespace koolreport\datasources;
use \koolreport\core\DataSource;
use \koolreport\core\Utility;

/**
 * ReportDataSource helps to connect to other report to get data
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class ReportDataSource extends DataSource
{
    /**
     * List of report used as sources
     * 
     * @var array $reports List of report used as sources
     */
    static $reports;

    /**
     * The name of datastore in source report
     * 
     * @var string The name of datastore in source report
     */
    protected $storeName;

    /**
     * Report's object
     * 
     * @var KoolReport Report's object
     */
    protected $report;

    /**
     * Datasource initiation
     * 
     * @return null
     */
    protected function onInit()
    {
        $key = Utility::get($this->params, "key", "");
        $reportSource = Utility::get($this->params, "report");
        $reportParams = Utility::get($this->params, "params", array());

        if ($reportSource==null) {
            throw new \Exception("ReportDataSource require 'source' parameter which is the classname of source report");
        }

        if (ReportDataSource::$reports==null) {
            ReportDataSource::$reports = array();
        }

        if (isset(ReportDataSource::$reports[$reportSource.$key])) {
            $this->report = ReportDataSource::$reports[$reportSource.$key];
        } else {
            try
            {
                $this->report = new $reportSource($reportParams);
            }
            catch(\Exception $e)
            {
                throw new \Exception("Could not create report '$reportSource'");
            }
            
            ReportDataSource::$reports[$reportSource.$key]=$this->report;
            $this->report->run();
        }
    }

    /**
     * Set the datastore name 
     * 
     * @param string $name Name of datastore
     * 
     * @return ReportDataStore This datasource object
     */
    public function dataStore($name)
    {
        $this->storeName = $name;
        return $this;
    }

    /**
     * Start piping data
     * 
     * @return null
     */
    public function start()
    {
        $this->sendMeta($this->report->dataStore($this->storeName)->meta());
        $this->startInput(null);
        $this->report->dataStore($this->storeName)->popStart();
        while ($row = $this->report->dataStore($this->storeName)->pop()) {
            $this->next($row, $this);
        }
        $this->endInput(null);
    }
}