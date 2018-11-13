<?php
/**
 * This file contains process to rename a column including data and meta data.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/* Usage
 * ->pipe(new ColumnRename(array(
 * 		"amount"=>"sale_amount",
 * 		"time"=>"timing",
 * )))
 * 
 */
namespace koolreport\processes;
use \koolreport\core\Utility;
use \koolreport\core\Process;
 
class ColumnRename extends Process
{
	protected function onMetaReceived($metaData)
	{
        $keys = array_keys($metaData["columns"]);
        $values = array_values($metaData["columns"]);

        for($i=0;$i<count($keys);$i++)
        {
            if(isset($this->params[$keys[$i]]))
            {
                $keys[$i] = $this->params[$keys[$i]];
            }
        }
        $metaData["columns"] = array_combine($keys,$values);
        return $metaData;
	}
	
	public function onInput($row)
	{
        $keys = array_keys($row);
        $values = array_values($row);
        for($i=0;$i<count($keys);$i++)
        {
            if(isset($this->params[$keys[$i]]))
            {
                $keys[$i] = $this->params[$keys[$i]];
            }
        }
        $this->next(array_combine($keys,$values));        
    }
}

