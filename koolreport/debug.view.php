<?php    
use \koolreport\widgets\koolphp\Table;
?>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<div class="container">



<?php
foreach($this->dataStores as $name=>$dataStore)
{
?>
    <h1>$this->dataStore(<span class='text-success'>'<?php echo $name; ?>'</span>)</h1>
    <?php
    Table::create(array(
        'dataStore'=>$dataStore,
        'cssClass'=>array(
            'table'=>'table table-hover'
        )
    ));
    ?>
<?php
}
?>

</div>