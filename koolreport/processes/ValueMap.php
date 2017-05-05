<?php
/**
 * This file contains class to map data value to another.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * ->pipe(new ValueMap(array(
 * 		"columnName"=>array(
 * 			"1"=>"One",
 * 			"2"=>"Two",
 *      "{func}"=>function($value) {
 *        return 'prefix' . $value;
 *      },
 * 			"{meta}"=>array(
 * 				"type"=>"string"
 * 			)
 * 		)
 * )))
 * 
 * 
 */
namespace koolreport\processes;
use \koolreport\core\Process;
use \koolreport\core\Utility;

class ValueMap extends Process
{
	protected $mapFuncs = array();
  
	protected function onMetaReceived($metaData)
	{
		foreach($this->params as $cName=>$cMap)
      if (is_array($cMap))
      {
        $cMeta = Utility::get($cMap,"{meta}");
        if($cMeta)
        {
          $override = Utility::get($cMeta,"{override}",false);
          if(isset($cMeta["{override}"]))
          {
            unset($cMeta["{override}"]);
          }
          if($override)
          {
            $metaData["columns"][$cName] = $cMeta;
          }
          else
          {
            $metaData["columns"][$cName] = array_merge($metaData["columns"][$cName],$cMeta);
          }
        }
        $cFunc = Utility::get($cMap,"{func}");
        if (is_callable($cFunc))
          $this->mapFuncs[$cName] = $cFunc;
      }
		return $metaData;
	}
		
	protected function onInput($data)
	{
		foreach($this->params as $cName=>$cMap) {
      if (isset($this->mapFuncs[$cName]))
        $data[$cName] = $this->mapFuncs[$cName]($data[$cName]);
      else if (is_array($cMap)) 
        $data[$cName] = Utility::get($cMap,$data[$cName],$data[$cName]);
		}
		$this->next($data);
	}
}