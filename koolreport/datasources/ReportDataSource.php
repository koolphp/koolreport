<?php
/**
 * This file contains class to pull data from other reports.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\datasources;
use \koolreport\core\DataSource;
use \koolreport\core\Utility;

class ReportDataSource extends DataSource
{
    static $reports;
    protected $storeName;
    protected $report;

    protected function onInit()
    {
        $key = Utility::get($this->params,"key","");
        $reportSource = Utility::get($this->params,"report");
        $reportParams = Utility::get($this->params,"params",array());

        if($reportSource==null)
        {
            throw new \Exception("ReportDataSource require 'source' parameter which is the classname of source report");
        }

        if(ReportDataSource::$reports==null)
        {
            ReportDataSource::$reports = array();
        }

        if(isset(ReportDataSource::$reports[$reportSource.$key]))
        {
            $this->report = ReportDataSource ::$reports[$reportSource.$key];
        }
        else
        {
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

    public function dataStore($name)
    {
        $this->storeName = $name;
        return $this;
    }

    public function start()
    {
        $this->sendMeta($this->report->dataStore($this->storeName)->meta());
        $this->startInput(null);
        $this->report->dataStore($this->storeName)->popStart();
        while($row = $this->report->dataStore($this->storeName)->pop())
        {
            $this->next($row,$this);
        }
        $this->endInput(null);
    }
}