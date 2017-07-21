<?php

require_once("../../functions.php");

if(isset($_GET['id'])){
    
    $query = query("delete from reports where report_id = ".escape_string($_GET['id']));
    confirm($query);
    
    set_message("Report deleted");
    
    redirect("../../../admin/index.php?reports");
    
}else{
    redirect("../../../admin/index.php?reports");
}

?>