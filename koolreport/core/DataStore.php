<?php
/**
 * This file contains foundation class for datastore
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\core;

class DataStore extends Node
{
	protected $dataset;
	protected $params;
	protected $report;
    protected $index=-1;
    
  
	public function __construct($report,$params=null)
	{
		parent::__construct();
		$this->report = $report;
		$this->params = $params;
		$this->dataset = array();
		$this->onInit();
	}
	
	protected function onInit()
	{
		
	}

	public function onInput($data)
	{
		array_push($this->dataset,$data);
	}
    
    public function countData()
    {
        return count($this->dataset);
    }	
	
	public function data()
	{
		return $this->dataset;
	}
    
    public function popStart()
    {
        // Start poping data, reset the index  to -1
        $this->index = -1;
    }
    public function getPopIndex()
    {
        return $this->index;
    }
    
    public function pop()
    {
        $this->index++;
        return Utility::get($this->dataset,$this->index);
    }	
}