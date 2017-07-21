<?php

require_once("../../functions.php");

if(isset($_GET['id'])){
    
    $query = query("delete from users where user_id = ".escape_string($_GET['id']));
    confirm($query);
    
    set_message("User deleted");
    
    redirect("../../../admin/index.php?users");
    
}else{
    redirect("../../../admin/index.php?users");
}

?>