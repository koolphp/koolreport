<?php

namespace koolreport\widgets\koolphp;
use \koolreport\core\Widget;
use \koolreport\core\Utility;

class Table extends Widget
{
	protected $dataStore;
	protected $columns;
	protected $cssClass;
    protected $removeDuplicate;
	
	protected function onInit()
	{
		$this->dataStore = Utility::get($this->params,"dataStore",null);
        if($this->dataStore==null)
        {
            throw \Exception("dataStore is required in Table widget");
        }
		$this->columns = Utility::get($this->params,"columns",array());
		$this->removeDuplicate = Utility::get($this->params,"removeDuplicate",array());
		$this->cssClass = Utility::get($this->params,"cssClass",array());
	}

	public function render()
	{
		
        $meta = $this->dataStore->meta();
        $showColumnKeys = array();
        
        if($this->columns==array())
        {
            $this->dataStore->popStart();
            $row = $this->dataStore->pop();
            $showColumnKeys = array_keys($row);
        }
        else
        {
            foreach($this->columns as $cKey=>$cValue)
            {
                if(gettype($cValue)=="array")
                {
                    $meta["columns"][$cKey] =  array_merge($meta["columns"][$cKey],$cValue);                
                    array_push($showColumnKeys,$cKey);
                }
                else
                {
                    array_push($showColumnKeys,$cValue);
                }
            }            
        }
        
		
		//Remove Duplicate
        $span = null;
		$groupColumns = $this->removeDuplicate;
            
        if($groupColumns!=array())
        {
            $span = array();
            $dup = array();
            $this->dataStore->popStart();
			while($row=$this->dataStore->pop())
			{
			    $i = $this->dataStore->getPopIndex();
				$sRow = array();
				for($j=0;$j<count($groupColumns);$j++)
				{
					$gColumn = $groupColumns[$j];
					if(!isset($dup[$gColumn]))
					{
						$dup[$gColumn] = array(
							"firstIndex"=>$i,
							"value"=>$row[$gColumn]
						);
						$sRow[$gColumn] = 1;
					}
					else
					{
						if($row[$gColumn] == $dup[$gColumn]["value"])
						{
							if(isset($groupColumns[$j-1]) && isset($span[$i][$groupColumns[$j-1]]) && $span[$i][$groupColumns[$j-1]]==1)
							{
								$sRow[$gColumn] = 1;
								$dup[$gColumn]["value"] = $row[$gColumn];
								$dup[$gColumn]["firstIndex"] = $i;								
							}
							else
							{
								$span[$dup[$gColumn]["firstIndex"]][$gColumn]++;
								$sRow[$gColumn] = 0;								
							}
						}
						else
						{
							$sRow[$gColumn] = 1;
							$dup[$gColumn]["value"] = $row[$gColumn];
							$dup[$gColumn]["firstIndex"] = $i;
						}
					}
				}
				array_push($span,$sRow);
			}
		}
		
		
		//Prepare data
		$this->template("Table",array(
			"showColumnKeys"=>$showColumnKeys,
			"span"=>$span,
			"meta"=>$meta,
		));
	}	
	
	static function create($params)
	{
		$component = new Table($params);
		$component->render();
	}
}