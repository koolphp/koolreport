<?php
/**
 * This file contains the most common used functions for KoolReport.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\core;

class Utility
{
    static $_uniqueId;
    
    static function getUniqueId()
    {
        Utility::$_uniqueId++;
        return uniqid().Utility::$_uniqueId;
    }

    static function guessType($value)
	{
		$map = array(
			"float"=>"number",
			"double"=>"number",
			"int"=>"number",
			"integer"=>"number",
			"bool"=>"number",
			"numeric"=>"number",
			"string"=>"string",
		);

		$type = strtolower(gettype($value));
		foreach($map as $key=>$value)
		{
			if(strpos($type,$key)!==false)
			{
				return $value;
			}			
		}
		return "unknown";
	}


    static function recurse_copy($src,$dst) { 
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    Utility::recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    } 	
    static function format($value,$format)
    {
        $type = Utility::get($format,"type","unknown");
        switch($type)
        {
            case "number":
                $decimals = isset($format["decimals"])?$format["decimals"]:0;
                $dec_point = isset($format["decimalPoint"])?$format["decimalPoint"]:".";
                $thousand_sep = isset($format["thousandSeparator"])?$format["thousandSeparator"]:",";
                $prefix = isset($format["prefix"])?$format["prefix"]:"";
                $suffix = isset($format["suffix"])?$format["suffix"]:"";
                return $prefix.number_format($value,$decimals,$dec_point,$thousand_sep).$suffix;
                break;
            case "string":
                $prefix = isset($format["prefix"])?$format["prefix"]:"";
                $suffix = isset($format["suffix"])?$format["suffix"]:"";
                return $prefix.$value.$suffix;
            break;
            case "datetime":
                $dateFormat = Utility::get($format,"format","Y-m-d H:i:s");
            case "date":
                $dateFormat = isset($dateFormat)?$dateFormat:Utility::get($format,"format","Y-m-d");
            case "time":
                $dateFormat = isset($dateFormat)?$dateFormat:Utility::get($format,"format","H:i:s");
                $displayFormat = Utility::get($format,"displayFormat");
                if($displayFormat)
                {
                    return \DateTime::createFromFormat($dateFormat,$value)->format($displayFormat);
                }
                break;
        }
        return $value;
    }
    
    static function getClassName($obj)
    {
        $reflection = new \ReflectionClass($obj);
        return $reflection->getShortName();
    }
    
    static function mark_js_function(&$obj,&$marks=array()){
        foreach($obj as $k=>&$v)
        {
            switch(gettype($v))
            {
                case "object":
                case "array":
                    Utility::mark_js_function($v,$marks);
                    break;
                case "string":
                    $tsv = trim(strtolower($v));
                    if(strpos($tsv,"function")===0 && strrpos($tsv,"}")===strlen($tsv)-1)
                    {
                        $marks[] = trim($v);
                        $obj[$k] = "--js(".(count($marks)-1).")";
                    }
                    break;
            }
        }
        return $marks;
    }

    static function jsonEncode($object,$option=0)
    {
        $marks = Utility::mark_js_function($object);
        $text = json_encode($object,$option);
        foreach($marks as $i=>$js)
        {
            $text = str_replace("\"--js($i)\"",$js,$text);
        }
        return $text;
    }
    
    static function isAssoc($arr)
    {
        if(gettype($arr)!="array")
        {
            return false;
        }
        if($arr===null || $arr===array()) return false;
        if(array_keys($arr)===range(0,count($arr)-1)) return false;
        return true;
    }
    static function get($arr,$key,$default=null)
    {
        if($arr===null)
        {
            return $default;
        }
        return isset($arr[$key])?$arr[$key]:$default;
    }
    static function init(& $arr, $key, $default = null) {
        if (! isset($arr[$key]))
            $arr[$key] = $default;
        return $arr[$key];
    }
    static function getArray($arr,$key,$default=array())
    {
        $value = Util::get($arr,$key);
        return ($value!=null)?explode(',', $value):$default;
    }
    static function filterIn($arr,$keys)
    {
        $keys = explode(",", $keys);
        $result = array();
        foreach($arr as $key=>$value)
        {
            if(in_array($key, $keys))
            {
                $result[$key] = $value;
            }
        }
        return $result;
    }
    static function filterOut($arr,$keys)
    {
        $keys = explode(",", $keys);
        $result = array();
        foreach($arr as $key=>$value)
        {
            if(!in_array($key, $keys))
            {
                $result[$key] = $value;
            }
        }
        return $result;
    }		
    static function strReplace($str,$params)
    {
        foreach($params as $k=>$v)
        {
            $str = str_replace($k, $v, $str);
        }
        return $str;
    }
    static function getClassPath($obj)
    {
        $class_info = new \ReflectionClass($obj);
        return $class_info->getFileName();		
    }
    static function prettyPrint($arr) {
      echo '<pre>';
      echo json_encode($arr, JSON_PRETTY_PRINT), PHP_EOL;
      echo '</pre>';  
    }
}
