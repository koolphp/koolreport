<?php
/**
 * This file is template file of Google Chart 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

use \koolreport\core\Utility;
?>
<div id="<?php echo $this->name; ?>" style="<?php if ($this->width) echo "width:".$this->width.";"; ?><?php if ($this->height) echo "height:".$this->height.";"; ?>"></div>
<script type="text/javascript">
    KoolReport.widget.init(<?php echo json_encode($this->getResources()); ?>,function(){
        KoolReport.google.chartLoader.load("<?php echo $this->stability; ?>","<?php echo $this->package; ?>","<?php echo $this->mapsApiKey; ?>");
        <?php echo $this->name; ?> = new KoolReport.google.chart("<?php echo $chartType; ?>","<?php echo $this->name; ?>",<?php echo json_encode($cKeys);?>,<?php echo json_encode($data);?>,<?php echo json_encode($options);?>);
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