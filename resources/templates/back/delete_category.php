<?php

require_once("../../functions.php");

if(isset($_GET['id'])){
    
    $query = query("delete from categories where cat_id = ".escape_string($_GET['id']));
    confirm($query);
    
    set_message("Category deleted");
    
    redirect("../../../admin/index.php?categories");
    
}else{
    redirect("../../../admin/index.php?categories");
}

?>