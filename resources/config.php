<?php

ob_start();

session_start();


define("DS", DIRECTORY_SEPARATOR);

define("TEMPLATE_FRONT", __DIR__.DS."templates/front");
define("TEMPLATE_BACK", __DIR__.DS."templates/back");

define("UPLOAD_DIRECTORY", __DIR__.DS."uploads");

define('DB_HOST', 'us-cdbr-iron-east-03.cleardb.net'); /*Database server*/
define('DB_NAME', 'heroku_4be8feaf98d506b'); /*Database Name*/
define('DB_USER', 'bb1056b1b147ff'); /*Database username*/
define('DB_PWD', 'a8841a07');



function connectDB() {
    $link = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);
    if($link->connect_error) {
        die("Connection failed: ".$link->connect_error);
    }
    

    return $link;
}


$link = connectDB();


?>