<?php

require_once("../../functions.php");

if(isset($_GET['id'])){
    
    $query = query("delete from orders where order_id = ".escape_string($_GET['id']));
    confirm($query);
    
    set_message("Order deleted");
    
    redirect("../../../admin/index.php?orders");
    
}else{
    redirect("../../../admin/index.php?orders");
}

?>