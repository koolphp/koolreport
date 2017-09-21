<?php
require_once "Products.php";
$products = new Products();
$products->run()->render();
?>    
