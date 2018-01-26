<?php
/**
 * This file contains foundation class to handle resource (css/js) in KoolReport
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\core;
use \koolreport\core\Utility;

class ResourceManager extends Base
{
    protected $tags;
    protected $report;

    public function __construct($report)
    {
        parent::__construct();
        $this->report = $report;
    }

    public function init()
    {
        $this->tags = array();
        $this->report->fireEvent("OnResourceInit");
        return $this;
    }

    public function addScriptFileOnBegin($src,$options=array())
    {
        return $this->addScriptFile($src,$options,'begin');
    }
    public function addScriptFileOnEnd($src,$options=array())
    {
        return $this->addScriptFile($src,$options,'end');
    }
    protected function addScriptFile($src,$options=array(),$at)
    {
        $options["type"] = Utility::get($options,"type","text/javascript");
        $options["src"] = $src;
        $this->tags[md5($src)] = array(
            "at"=>$at,
            "tag"=>"script",
            "options"=>$options,
            "content"=>"",
        );
        return $this;
    }


    public function addScriptOnBegin($script,$options=array())
    {
        return $this->addScript($script,$options,'begin');
    }
    public function addScriptOnEnd($script,$options=array())
    {
        return $this->addScript($script,$options,'end');
    }
    protected function addScript($script,$options=array(),$at='begin')
    {
        $options["type"] = Utility::get($options,"type","text/javascript");
        $this->tags[md5($script)] = array(
            "at"=>$at,
            "tag"=>"script",
            "options"=>$options,
            "content"=>$script,
        );
        return $this;
    }



    public function addStyle($style,$options=array())
    {
        $options["type"] = Utility::get($options,"type","text/css");

        $this->tags[md5($style)] = array(
            "at"=>'begin',
            "tag"=>"style",
            "options"=>$options,
            "content"=>$style,
        );
        return $this;
    }

    public function addCssFile($href,$options=array())
    {
        $options["type"] = Utility::get($options,"type","text/css");
        $options["rel"] = Utility::get($options,"rel","stylesheet");
        $options["href"] = $href;

        $this->tags[md5($href)] = array(
            "at"=>'begin',
            "tag"=>"link",
            "options"=>$options,
            "content"=>"",
        );
        return $this;
    }

    public function addLinkTag($options)
    {
        $unique = "u";
        foreach($options as $key=>$value)
        {
            $unique.="[$key=$value]";
        }
        $this->tags[md5($unique)] = array(
            "at"=>'begin',
            "tag"=>"link",
            "options"=>$options,
            "content"=>"",
        );
        return $this;
    }

    public function renderTag($tag)
    {
        $str = "<".$tag["tag"];
        foreach($tag["options"] as $key=>$value)
        {
            $str.=" $key='$value'";
        }
        $str.=">".$tag["content"]."</".$tag["tag"].">";
        return $str;
    }

    public function process(&$content)
    {
        //Add resources to the content
        $begin = "";
        $end = "";

        foreach($this->tags as $tag)
        {
            if($tag["at"]=="begin")
            {
                $begin.=$this->renderTag($tag);
            }
            else
            {
                $end.=$this->renderTag($tag);
            }
        }

        if($begin!=='')
        {
			$count=0;
			$content=preg_replace('/(<body\b[^>]*>)/is','$1<###begin###>',$content,1,$count);
			if($count)
				$content=str_replace('<###begin###>',$begin,$content);
			else
				$content=$begin.$content;
        }

        if($end!=='')
        {
			$count=0;
			$content=preg_replace('/(<\\/body\s*>)/is','<###end###>$1',$content,1,$count);
			if($count)
				$content=str_replace('<###end###>',$end,$content);
			else
				$content=$content.$end;
        }
    }
}