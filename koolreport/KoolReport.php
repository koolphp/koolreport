<?php
namespace koolreport;
use \koolreport\core\Base;
use \koolreport\core\Utility;

class KoolReport extends Base
{
	protected $params;
	protected $dataSources;
	protected $dataStores;
	
	public function __construct($params=array())
	{		
		$this->params = $params;
		$this->dataSources = array();
		$this->dataStores = array();
		$this->setup();
	}
	
	protected function setup()
	{

	}
	
	public function settings()
	{
		return array();
	}
	
	protected function src($name) {
		$dataSources = Utility::get($this->settings(),"dataSources",array());
		$dataSourceSetting = Utility::get($dataSources,$name);
		if(!$dataSourceSetting)
		{
			throw new \Exception("Datasource not found '$name'");
			return false;
		}
		$dataSourceClass = Utility::get($dataSourceSetting,"class","\koolreport\datasources\PdoDataSource");
		$dataSourceClass = str_replace("/","\\",$dataSourceClass);
		$dataSource = new $dataSourceClass($dataSourceSetting);
		array_push($this->dataSources,$dataSource);
		return $dataSource;
	}
	
	
	protected function newDataStore($params)
	{
        $dataStoreClass = utility::get($params,"class",'\koolreport\core\DataStore'); 
        $dataStoreClass = str_replace("/","\\",$dataStoreClass);   
		return new $dataStoreClass($this,$params);
	} 
	
	public function dataStore($name)
	{				
		$settings = $this->settings();
		$dataStoreParams = Utility::get($settings,"dataStore");
		if(gettype($name)=="array")
		{
			$rStores = array();
			foreach($name as $dataStoreName)
			{
				if(!isset($this->dataStores[$dataStoreName]))
				{
					$this->dataStores[$dataStoreName] = $this->newDataStore($dataStoreParams);
				}
				array_push($rStores,$this->dataStores[$name]);
			}	
			return $rStores;
		}
		else
		{
			if(!isset($this->dataStores[$name]))
			{
				$this->dataStores[$name] = $this->newDataStore($dataStoreParams);
			}
			return $this->dataStores[$name];						
		}
	}
	
	public function run()
	{
		foreach($this->dataSources as $dataSource)
		{
			$dataSource->start();
		}
		return $this;
	}
	
		
	public function render($view=null,$return=false)
	{
		if($view===null)
		{
			$view = Utility::getClassName($this);
		}
        else
        {
            if(gettype($view)=="boolean")
            {
                $view = Utility::getClassName($this);
                $return = $view;    
            }
        }
		$currentDir = dirname(Utility::getClassPath($this));
		ob_start();
		$GLOBALS["__ACTIVE_KOOLREPORT__"] = $this;
		include($currentDir."/".$view.".view.php");
		$content = ob_get_clean();
		
		if($return)
		{
			return $content;	
			
		}
		else
		{
			echo $content;
		}
	}
}
