<?php
require_once "SalesQuarters.php";
$salesYear = isset($_POST['salesYear']) ? $_POST['salesYear'] :
    array(2003, 2004, 2005);
$SalesQuarters = new SalesQuarters(array(
  'salesYear' => $salesYear
));

?>    
<!DOCTYPE html>
<html>
  <head>
    <title>Sales By Quarters</title>
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../../assets/css/example.css" />
  </head>
  <body>
    <table class='table container box-all'>
      <tr>
        <td>
          <div class='box-filter'>
            <div>Sales years:</div>
            <form id='yearForm' method="post">
              <div>
                <input id="y2003" type="checkbox" name="salesYear[]" value="2003"
                  <?php echo in_array(2003, $salesYear) ? 'checked' : '' ?>
                >
                <label for="y2003">2003</label>
              </div>
              <div>
                <input id="y2004" type="checkbox" name="salesYear[]" value="2004"
                  <?php echo in_array(2004, $salesYear) ? 'checked' : '' ?>
                >
                <label for="y2004">2004</label>
              </div>
              <div>
                <input id="y2005" type="checkbox" name="salesYear[]" value="2005"
                  <?php echo in_array(2005, $salesYear) ? 'checked' : '' ?>
                >
                <label for="y2005">2005</label>
              </div>
              <button type="reset" value="Reset">Reset</button>
              <button type="submit">Submit</button>
            </form>
          </div>
        </td>
        <td>
            <?php $SalesQuarters->run()->render();?>
        </td>
      </tr>
    </table>
  
  </body>
</html>