<?php
/**
 * This file is wrapper class for Google Timeline 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\widgets\google;
use \koolreport\core\Utility;


class Timeline extends Chart
{

	protected function onInit()
	{
		parent::onInit();

	}

	protected function loadLibrary()
	{
		$this->getReport()->getResourceManager()
		->addScriptOnBegin("google.charts.load('current', {'packages':['timeline']})");
	}

	protected function newClientDate($value,$meta)
	{
		$format = Utility::get($meta,"format");
		$type = Utility::get($meta,"type");
		if($format==null)
		{
			switch($type)
			{
				case "date":
					$format = "Y-m-d";
					$toFormat = "Y,m,d";
					break;
				case "time":
					$format = "H:i:s";
					$toFormat = "0,0,0,H,i,s";
					break;
				case "datetime":
				default:
					$format = "Y-m-d H:i:s";
					$toFormat = "Y,m,d,H,i,s";
					break;
			}
		}
		return "new Date(".\DateTime::createFromFormat($format,$value)->format($toFormat).")";
	}

	public function render()
	{

        $this->getReport()->getResourceManager()
            ->addScriptFileOnBegin('https://www.gstatic.com/charts/loader.js');


		$columns = $this->getColumnSettings();

		// $columnByRoles = array(
		// 	"rowLabel"=>null,
		// 	"barLabel"=>null,
		// 	"tooltip"=>null,
		// 	"start"=>null,
		// 	"end"=>null,
		// );

		// foreach($columns as $cKey=>$cSettings)
		// {
		// 	$role = Utility::get($cSettings,"role",null);
		// 	if(isset($columnByRoles[$role])===false)
		// 	{
		// 		$columnByRoles[$role] =$cSettings;
		// 		$columnByRoles[$role]["name"] = $cKey;
		// 	}
		// }

		//Update options
		$options = $this->options;
		if($this->title)
		{
			$options["title"] = $this->title;
		}
		if($this->colorScheme)
		{
			$options["colors"] = $this->colorScheme;
		}

		$this->template('Timeline',array(
                "chartId"=>$this->chartId,
                "options"=>$options,
				"columns"=>$columns,
        ));
	}
}
