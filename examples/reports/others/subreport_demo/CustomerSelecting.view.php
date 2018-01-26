<?php
    use \koolreport\inputs\Select;
?>

<div class="form-group">
    <label>Select country:</label>
    <?php
    Select::create(array(
        "name"=>"country",
        "dataStore"=>$this->dataStore("countries"),
        "defaultOption"=>array("--"=>null),
        "dataBind"=>"country",
        "clientEvents"=>array(
            "change"=>"function(){
                subReport.update('customerselecting',{
                    country:$('#country').val(),
                });
                subReport.update('listorders');
            }",
        ),
        "attributes"=>array(
            "class"=>"form-control",
        )
    ));
    ?>                
</div>

<?php
if($this->params["country"])
{
?>
    <div class="form-group">
    <label>Select customer in <?php echo $this->params["country"]; ?>:</label>
        <?php
        Select::create(array(
            "name"=>"customer",
            "dataStore"=>$this->dataStore("customers"),
            "defaultOption"=>array("--"=>null),
            "dataBind"=>array(
                "text"=>"customerName",
                "value"=>"customerNumber"
            ),
            "clientEvents"=>array(
                "change"=>"function(){
                    subReport.update('customerselecting',{
                        country:$('#country').val(),
                        customer:$('#customer').val(),
                    });                     
                }",
            ),
            "attributes"=>array(
                "class"=>"form-control",
            )
        ));    
        ?>
    </div>

<?php    
}
?>

<?php
if(isset($this->params["customer"]) && $this->params["customer"]!=null)
{
?>
    <div class="form-group text-center">
        <button onclick="listOrders()" class="btn btn-success">List Orders</button>
    </div>
<?php    
}
?>

<script type="text/javascript">
    function listOrders()
    {
        subReport.update("listorders",{
            "customerNumber":$('#customer').val(),
        });
    }
</script>