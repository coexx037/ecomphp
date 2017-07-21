<?php

ob_start();

session_start();


define("DS", DIRECTORY_SEPARATOR);

define("TEMPLATE_FRONT", __DIR__.DS."templates/front");
define("TEMPLATE_BACK", __DIR__.DS."templates/back");

define("UPLOAD_DIRECTORY", __DIR__.DS."uploads");

define('DB_HOST', 'xxx'); /*Database server*/
define('DB_NAME', 'xxx'); /*Database Name*/
define('DB_USER', 'xxx'); /*Database username*/
define('DB_PWD', 'xxx');



function connectDB() {
    $link = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);
    if($link->connect_error) {
        die("Connection failed: ".$link->connect_error);
    }
    

    return $link;
}


$link = connectDB();


?>