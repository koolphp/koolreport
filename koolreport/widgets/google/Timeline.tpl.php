<?php
/**
 * This file is template file of Google Chart 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

use \koolreport\core\Utility;
?>
<div id="<?php echo $chartId; ?>" style="<?php if ($this->width) echo "width:".$this->width.";"; ?><?php if ($this->height) echo "height:".$this->height.";"; ?>"></div>

<script type="text/javascript">
    googleChartLoader.load("<?php echo $this->stability; ?>","<?php echo $this->package; ?>");
    var tldata = [[]];
    <?php
    foreach($columns as $cKey=>$column)
        if($column!=null)
        {
            $column["id"] = $cKey;
        ?>
        tldata[0].push(<?php echo json_encode($column); ?>);
        <?php    
        }
        $this->dataStore->popStart();
        while($row=$this->dataStore->pop())
        {
        ?>
        tldata.push([<?php
            foreach($columns as $cKey=>$column)
            {
                if($column!=null)
                {
                    switch($column["type"])
                    {
                        case "date":
                        case "datetime":
                        case "time":
                            echo $this->newClientDate($row[$cKey],$column).",";
                        break;
                        case "number":
                            echo CJSON::encode(array(
                                "v"=>$row[$cKey],
                                "f"=>Utility::format($row[$cKey],$column),
                            ));
                        break;
                        case "string":
                        default:
                            echo str_replace("{value}",addslashes($row[$cKey]),"\"{value}\",");
                        break;
                    }    
                }
            }        
        ?>]);
        <?php    
        }
    ?>
    
    var <?php echo $chartId; ?> = new GoogleChart("Timeline","<?php echo $chartId; ?>",tldata,<?php echo json_encode($options); ?>);    
</script>