<?php
use \koolreport\core\Utility;
?>
<?php $this->loadLibrary(); ?>
<div id="<?php echo $chartId; ?>" style="width: <?php echo $this->width; ?>; height: <?php echo $this->height; ?>"></div>

<script type="text/javascript">
  function draw_<?php echo $chartId; ?>()
  {
    var data = new google.visualization.arrayToDataTable(<?php echo json_encode($data);?>);
    var options = <?php echo json_encode($options); ?>;
    var chart = new google.visualization.<?php echo $chartType; ?>(document.getElementById('<?php echo $chartId; ?>'));    
    chart.draw(data, options);
  }
  function loadChart_<?php echo $chartId; ?>() {
    google.charts.setOnLoadCallback(draw_<?php echo $chartId; ?>);
    window.addEventListener('resize',draw_<?php echo $chartId; ?>);
  }
</script>