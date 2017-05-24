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
    var data = new google.visualization.arrayToDataTable(<?php echo json_encode($data);?>);
    var options = <?php echo json_encode($options); ?>;
    var chart = new google.visualization.<?php echo $chartType; ?>(document.getElementById('<?php echo $chartId; ?>'));    
    chart.draw(data, options);
  }
  google.charts.setOnLoadCallback(draw_<?php echo $chartId; ?>);
  window.addEventListener('resize',draw_<?php echo $chartId; ?>);
</script>