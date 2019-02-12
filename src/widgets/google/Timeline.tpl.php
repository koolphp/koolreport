<?php
/**
 * Timeline template
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

use \koolreport\core\Utility;
?>
<div id="<?php echo $this->name; ?>" style="<?php if ($this->width) echo "width:".$this->width.";"; ?><?php if ($this->height) echo "height:".$this->height.";"; ?>"></div>

<script type="text/javascript">
KoolReport.widget.init(<?php echo json_encode($this->getResources()); ?>,function(){
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
    
    <?php echo $this->name; ?> = new KoolReport.google.chart("Timeline","<?php echo $this->name; ?>",<?php echo json_encode(array_keys($columns)); ?>,tldata,<?php echo Utility::jsonEncode($options); ?>,<?php echo json_encode($loader); ?>);
    <?php
    if($this->pointerOnHover)
    {
        echo "$this->name.pointerOnHover=true;";    
    }
    ?>
    <?php
    foreach($this->clientEvents as $event=>$function)
    {
    ?>
        <?php echo $this->name; ?>.registerEvent("<?php echo $event; ?>",<?php echo $function; ?>);
    <?php
    }
    ?>
    <?php $this->clientSideReady(); ?>
});     
</script>