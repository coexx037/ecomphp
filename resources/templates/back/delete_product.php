<?php

require_once("../../functions.php");

if(isset($_GET['id'])){
    
    $query = query("delete from products where product_id = ".escape_string($_GET['id']));
    confirm($query);
    
    set_message("Product deleted");
    
    redirect("../../../admin/index.php?products");
    
}else{
    redirect("../../../admin/index.php?products");
}

?>