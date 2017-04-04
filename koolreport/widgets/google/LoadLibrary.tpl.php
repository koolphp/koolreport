<script type='text/javascript'>
  var scripts = document.getElementsByTagName('script');
  var isGoogleChartLoaded = false;
  var script;
  for (var i = 0; i<scripts.length; i+=1)
    if (scripts[i].src === 'https://www.gstatic.com/charts/loader.js') {
      isGoogleChartLoaded = true;
      script = scripts[i];
      break;      
    }
  if (! isGoogleChartLoaded) {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.addEventListener('load', function() {
      google.charts.load('<?php echo $zone; ?>', {'packages':<?php echo json_encode($packages); ?>});
      loadChart_<?php echo $chartId; ?>();
    });
    script.src = 'https://www.gstatic.com/charts/loader.js';
    document.head.appendChild(script);
  }
  else if (! window.google) {
    script.addEventListener('load', function() {
      loadChart_<?php echo $chartId; ?>();
    });
  }
  else {
    loadChart_<?php echo $chartId; ?>();
  }
</script>