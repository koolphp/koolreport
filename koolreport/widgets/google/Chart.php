<?php
/**
 * This file is wrapper class for Google Chart 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\widgets\google;
use \koolreport\core\Widget;
use \koolreport\core\Utility;
use \koolreport\core\DataStore;


class Chart extends Widget
{
    protected $chartId;
    protected $columns;
    protected $options;
    protected $type;
    protected $width;
    protected $height;
    protected $title;
    protected $colorScheme;
    protected $data;
    protected $clientEvents;
    protected $pointerOnHover;

    protected $package="corechart";
    protected $stability="current";

    protected function resourceSettings()
    {
        return array(
            "library"=>array("jQuery"),
            "folder"=>"clients",
            "js"=>array("googlechart.js"),
        );
    }

    protected function onInit()
    {
        $this->useDataSource();
        $this->useAutoName("gchart");

        $this->clientEvents = Utility::get($this->params,"clientEvents",array());        
        $this->columns = Utility::get($this->params,"columns",null);
        $this->options = Utility::get($this->params,"options",array());
        $this->width = Utility::get($this->params,"width","600px");
        $this->height = Utility::get($this->params,"height","400px");
        $this->title = Utility::get($this->params,"title");
        $this->pointerOnHover = Utility::get($this->params,"pointerOnHover");
        if($this->pointerOnHover===null)
        {
            if(isset($this->clientEvents["itemSelect"])
                ||isset($this->clientEvents["rowSelect"])
                ||isset($this->clientEvents["columnSelect"])
                ||isset($this->clientEvents["select"]))
            {
                $this->pointerOnHover = true;
            }
        }

        if(!$this->dataStore)
        {
            //Backward compatible with setting through "data"
			$data = Utility::get($this->params,"data");
			if(is_array($data))
			{
				if(count($data)>0)
				{
					$this->dataStore = new DataStore;
					$this->dataStore->data($data);
					$row = $data[0];
					$meta = array("columns"=>array());
					foreach($row as $cKey=>$cValue)
					{
						$meta["columns"][$cKey] = array(
							"type"=>Utility::guessType($cValue),
						);
					}
					$this->dataStore->meta($meta);	
				}
				else
				{
					$this->dataStore = new DataStore;
					$this->dataStore->data(array());
					$this->dataStore->meta(array("columns"=>array()));
				}	
			}
			if($this->dataStore==null)
			{
				throw new \Exception("dataSource is required");
				return;
			}
        }

        $this->type = Utility::getClassName($this);
        if($this->type=="Chart")
        {
            $this->type = Utility::get($this->params,"type");
        }
        //Color Scheme
        $colorScheme = Utility::get($this->params,"colorScheme");
        if($colorScheme!==null)
        {
            switch(gettype($colorScheme))
            {
                case "string":
                    $colorScheme = intval($colorScheme);
                case "integer":
                    //Get the color scheme from theme
                    $this->colorScheme = $this->getReport()->getColorScheme($colorScheme);
                break;
                case "array":
                    $this->colorScheme = $colorScheme;
                break;
            }
        }
        else
        {
            $this->colorScheme = $this->getReport()->getColorScheme();
        }
    }

    
    protected function typeConvert($type)
    {
        $map = array(
            "datetime"=>"datetime",
            "unknown"=>"string",
            "string"=>"string",
            "number"=>"number",
        );
        return isset($map[$type])?$map[$type]:"string";
    }	

    protected function getColumnSettings()
    {
        //If there is the user input columns then parse them to columns from user input
        //If the user does not input collumns then take the default by looking at data
        // Then mixed with default in meta
        $meta = $this->dataStore->meta();
        $columns=array();
        if($this->columns!=null)
        {
            foreach($this->columns as $cKey=>$cValue)
            {
                if(gettype($cValue)=="array")
                {
                    $columns[$cKey] = array_merge($meta["columns"][$cKey],$cValue);
                }
                else
                {
                    $columns[$cValue] = $meta["columns"][$cValue];
                }
            }
        }
        else
        {
            $this->dataStore->popStart();
            $row = $this->dataStore->pop();
            $keys = array_keys($row);
            foreach($keys as $ckey)
            {
                $columns[$ckey] = $meta["columns"][$ckey];
            }
        }
        return $columns;
    }

    protected function prepareData()
    {
        //Now we have $columns contain all real columns settings

        $columns = $this->getColumnSettings();        
        
        $data = array();
        $header = array();
        $columnExtraRoles = array("annotation","annotationText","certainty","emphasis","interval","scope","style","tooltip");
        foreach($columns as $cKey=>$cSetting)
        {
            array_push($header,"".Utility::get($cSetting,"label",$cKey));
            foreach($columnExtraRoles as $cRole)
            {
                if(isset($cSetting[$cRole]))
                {
                    array_push($header,array(
                        "role"=>$cRole
                    ));
                }
            }
        }
        array_push($data,$header);
        
        
        $this->dataStore->popStart();
        while($row = $this->dataStore->pop())
        {
            $gRow = array();
            foreach($columns as $cKey=>$cSetting)
            {
                $value = $row[$cKey];
                $cType = Utility::get($cSetting,"type","unknown");
                if($cType==="number")
                {
                    $value = floatval($value);
                }
                $fValue = Utility::format($value,$cSetting);
                
                array_push($gRow,
                    ($fValue===$value)?
                        $value:
                        array("v"=>$value,"f"=>$fValue)
                );
                
                foreach($columnExtraRoles as $cRole)
                {
                    if(isset($cSetting[$cRole]))
                    {
                        array_push($gRow,
                            (gettype($cSetting[$cRole])=="object")?
                                $cSetting[$cRole]($row):
                                $cSetting[$cRole]
                        );
                    }
                }
            }
            array_push($data,$gRow);
        }
        return $data;
    }
        
    protected function onRender()
    {
        if($this->dataStore->countData()>0)
        {
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
            //Render
            $this->template("Chart",array(
                "chartType"=>$this->type,
                "options"=>$options,
                "data"=>$this->prepareData(),
            ));			
        }
        else
        {
            $this->template("NoData");
        }
    }
}
