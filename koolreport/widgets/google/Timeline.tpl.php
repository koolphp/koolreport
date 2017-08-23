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
<?php $this->loadLibrary(); ?>
<div id="<?php echo $chartId; ?>" style="<?php if ($this->width) echo "width:".$this->width.";"; ?><?php if ($this->height) echo "height:".$this->height.";"; ?>"></div>

<script type="text/javascript">
    function draw_<?php echo $chartId; ?>()
    {
        var container = document.getElementById('<?php echo $chartId; ?>');
        var chart = new google.visualization.Timeline(container);
        var dataTable = new google.visualization.DataTable();
        var options = <?php echo json_encode($options); ?>;

        <?php
        foreach($columns as $cKey=>$column)
            if($column!=null)
            {
                $column["id"] = $cKey;
            ?>
            dataTable.addColumn(<?php echo json_encode($column); ?>);
            <?php    
            }
        ?>

        dataTable.addRows([
        <?php
        $this->dataStore->popStart();
        while($row=$this->dataStore->pop())
        {
            echo "[";
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
            echo "],";
        }
        ?>
        ]);

        chart.draw(dataTable, options);
    }
    google.charts.setOnLoadCallback(draw_<?php echo $chartId; ?>);
    window.addEventListener('resize',draw_<?php echo $chartId; ?>);
</script>