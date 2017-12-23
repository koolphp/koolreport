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
    var <?php echo $chartId; ?> = new GoogleChart("<?php echo $chartType; ?>","<?php echo $chartId; ?>",<?php echo json_encode($data);?>,<?php echo json_encode($options);?>);
    <?php
    foreach($this->clientEvents as $event=>$function)
    {
    ?>
        <?php echo $chartId; ?>.registerEvent("<?php echo $event; ?>",<?php echo $function; ?>);
    <?php
    }
    ?>
</script>