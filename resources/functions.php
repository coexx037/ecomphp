<?php
require_once('config.php');

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

$config = require('s3config.php');
require 'vendor/autoload.php';

$s3 = new S3Client([
    
    'version' => $config['s3']['version'],
    'region' => $config['s3']['region'],
    'credentials' => [
    
        'key' => $config['s3']['key'],
        'secret' => $config['s3']['secret']
        
    ]
    
    
]);

//var_dump($s3);
//helper functions

function last_id(){
    global $link;
    
    return mysqli_insert_id($link);
}


function set_message($msg){
    if(!empty($msg)){
        $_SESSION['message'] = $msg;
    }else{
        $msg = "";
    }
}

function display_message(){
    if(isset($_SESSION['message'])){
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}


function redirect($location){
    header("Location: $location");
}


function query($sql){
    
    global $link;
    
    return mysqli_query($link, $sql);
}

function confirm($result){
    global $link;
    
    if(!$result){
        die("Query Failed!".mysqli_error($link));
    }
}

function escape_string($string){
    global $link;
    
    return mysqli_real_escape_string($link, $string);
}

function fetch_array($result){
    return mysqli_fetch_array($result);
}

/**************************************Front end functions****************************/

//get products

function get_products(){
    
    $query = query("select * from products");
    
    confirm($query);
    
    while($row = fetch_array($query)){
        
$product_image = display_image($row['product_image']);
        
$product = <<<DELIMETER

<div class="col-sm-4 col-lg-4 col-md-4">
    <div class="thumbnail">
        <a href="item.php?id={$row['product_id']}"><img src="{$row['product_image']}" alt="">
    </div>
        <div class="caption">
            <h4 class="pull-right">&#36;{$row['product_price']}</h4>
            <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
            </h4>
            <a class="btn btn-primary" target="_blank" href="./resources/cart.php?add={$row['product_id']}">Add to Cart</a>
        </div>
</div>


DELIMETER;

echo $product;
        
    }
    
}

function get_categories(){
    
    $query = query("select * from categories");
    confirm($query);
    
    while($row = fetch_array($query)){
        
$category_links = <<<DELIMETER


<a href="category.php?id={$row['cat_id']}" class="list-group-item">{$row['cat_title']}</a>

DELIMETER;

echo $category_links;

    }
}


function get_products_by_category($id){
    
    $query = query("select * from products where product_category_id=".$id);
    
    confirm($query);
    
    while($row = fetch_array($query)){
        
$product_image = display_image($row['product_image']);
        
$product = <<<DELIMETER

<div class="col-md-3 col-sm-6 hero-feature">
<div class="thumbnail">
    <img src="{$row['product_image']}" alt="">
</div>
    <div class="caption">
        <h4>{$row['product_title']}</h4>
        <p>{$row['short_desc']}</p>
        <p>
            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
        </p>
    </div>
</div>


DELIMETER;

echo $product;
        
    }
    
}

function get_products_to_shop(){
    
    $query = query("select * from products");
    
    confirm($query);
    
    while($row = fetch_array($query)){
        
$product_image = display_image($row['product_image']);
        
$product = <<<DELIMETER

<div class="col-md-3 col-sm-6">
<div class="thumbnail">
    <img src="{$row['product_image']}" alt="">
</div>
        <h4>{$row['product_title']}</h4>
        <p>{$row['short_desc']}</p>
        <p>
            <a href="./resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
        </p>
</div>



DELIMETER;

echo $product;
        
    }
    
}


function login_user(){
    
    if(isset($_POST['submit'])){
        
        
        $username = escape_string($_POST['username']);
        $password = escape_string($_POST['password']);
        
        var_dump($username, $password);
        
        $query = query("select * from users where username = '{$username}' and password = '{$password}'");
        
        confirm($query);
        
        if(mysqli_num_rows($query) == 0){
            
            set_message("Your Password and Username combination is wrong.");
            
            redirect("login.php");
          
        }else{
            
            $_SESSION['username'] = $username;
            redirect("admin");
        }
        
    }
}

function send_message(){
    if(isset($_POST['submit'])){
        
        $to = "someemailaddres@gmail.com";
        $from_name = $_POST['name'];
        $subject = $_POST['subject'];
        $email = $_POST['email'];
        $message = $_POST['message'];
        
        $headers = "FROM: {$from_name} {$email}";
        
        $result = mail($to, $subject, $message, $headers);
        
        if(!$result){
            set_message("Sorry, we could not send it...");
        }else{
            set_message("Your message has been sent!");
        }
    }   
}
    
    
    
/***********************************Back end functions****************************************/

function display_orders(){
    
$query = query("select * from orders");
confirm($query);

while($row = fetch_array($query)){
    
$orders = <<<DELIMETER

<tr>
    <td>{$row['order_id']}</td>
    <td>{$row['order_amount']}</td>
    <td>{$row['order_transaction']}</td>
    <td>{$row['order_currency']}</td>
    <td>{$row['order_status']}</td>
    <td><a class="btn btn-danger" href="../resources/templates/back/delete_order.php?id={$row['order_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>

DELIMETER;

echo $orders;

    }
    
}
    
    
    
/**************************Admin products*************************************/

function display_image($picture){
    
    return "uploads".DS.$picture;
    
}


function get_products_in_admin(){
    
$query = query("select * from products");

confirm($query);

while($row = fetch_array($query)){
    
$category = show_product_category_title($row['product_category_id']);

$product_image = display_image($row['product_image']);
        
$product = <<<DELIMETER

<tr>
    <td>{$row['product_id']}</td>
    <td>{$row['product_title']}<br>
    <a href="index.php?edit_product&id={$row['product_id']}"><img src="{$row['product_image']}" alt=""></a>
    </td>
    <td>{$category}</td>
    <td>{$row['product_price']}</td>
    <td>{$row['product_quantity']}</td>
    <td><a class="btn btn-danger" href="../../resources/templates/back/delete_product.php?id={$row['product_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>


DELIMETER;

echo $product;
        
    }
    
}
    

function show_product_category_title($product_category_id){
    
    $category_query = query("select * from categories where cat_id = '{$product_category_id}' ");
    confirm($category_query);
    
    while($category_row = fetch_array($category_query)){
        
        return $category_row['cat_title'];
        
    }
    
}





/*************************************add products in admin**********************/

function add_product(){
    
    if(isset($_POST['publish'])){
        
        $product_title = escape_string($_POST['product_title']);
        $product_category_id = escape_string($_POST['product_category_id']);
        $product_price = escape_string($_POST['product_price']);
        $product_quantity = escape_string($_POST['product_quantity']);
        $product_description = escape_string($_POST['product_description']);
        $short_desc = escape_string($_POST['short_desc']);
        $name = escape_string($_FILES['file']['name']);
        $image_temp_location = escape_string($_FILES['file']['tmp_name']);
        
        $ext = explode('.', $name);
        $ext = strtolower(end($ext));
        
        $key = md5(uniqid());
        
        $temp_file_name = $key.".".$ext;
        
        
        
        global $s3;
        global $config;
        
        try{
            
            $s3->putObject([
            
                'Bucket' => $config['s3']['bucket'],
                'Key' => "uploads/{$temp_file_name}",
                'Body' => fopen($image_temp_location, 'rb'),
                'ACL' => "public-read"
            ]);
            
            
            
        } catch(S3Exception $e) {
            die("There was an error uploading to s3.".$e->getMessage());
        }
        
        $object = $s3->getObjectUrl($config['s3']['bucket'], "uploads/{$temp_file_name}");
        
        
        $query = query("insert into products (product_title, product_category_id, product_price, product_quantity, product_description, product_image, short_desc) values ('{$product_title}', '{$product_category_id}', '{$product_price}', '{$product_quantity}', '{$product_description}', '{$object}', '{$short_desc}')");
        $last_id = last_id();
        confirm($query);
        set_message("New product with id {$last_id} just added!");
        redirect("index.php?products");
        
        

        
    }
    
}



function show_categories($product_category_id){
    
$query = query("select * from categories");
confirm($query);

while($row = fetch_array($query)){
    
//$selected = ($row['cat_id'] == $product_category_id) ? "selected" : "";
        
$category_options = <<<DELIMETER


<option value="{$row['cat_id']}" {$selected}>{$row['cat_title']}</option>

DELIMETER;

echo $category_options;

    }
}


/*********************updating product******************************************/


function update_product(){
    
    if(isset($_POST['update'])){
        
        $product_title = escape_string($_POST['product_title']);
        $product_category_id = escape_string($_POST['product_category_id']);
        $product_price = escape_string($_POST['product_price']);
        $product_quantity = escape_string($_POST['product_quantity']);
        $product_description = escape_string($_POST['product_description']);
        $short_desc = escape_string($_POST['short_desc']);
        $product_image = escape_string($_FILES['file']['name']);
        $image_temp_location = escape_string($_FILES['file']['tmp_name']);
        
        
        if(empty($product_image)){
            
            $get_pic = query("select product_image from products where product_id = ".escape_string($_GET['id']));
            confirm($get_pic);
            
            while($pic = fetch_array($get_pic )){
                $product_image = $pic['product_image'];
            }
            
        }
        
        move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY.DS.$product_image);
        
        $query = "update products set ";
        $query.= "product_title = '{$product_title}', ";
        $query.= "product_price = '{$product_price}', ";
        $query.= "product_category_id = '{$product_category_id}', ";
        $query.= "product_quantity = '{$product_quantity}', ";
        $query.= "product_description = '{$product_description}', ";
        $query.= "short_desc = '{$short_desc}', ";
        $query.= "product_image = '{$product_image}' ";
        $query.= "where product_id = ".escape_string($_GET['id']);
        
        $update_query = query($query);
        confirm($update_query);
        set_message("Product just updated!");
        redirect("index.php?products");
        
        
    }
    
}


/***********************categories in admin***************************/

function show_categories_in_admin(){
    
$query = "select * from categories";
$category_query = query($query);
confirm($category_query);

while($row = fetch_array($category_query)){
    
$cat_id = $row['cat_id'];
$cat_title = $row['cat_title'];

$category = <<<DELIMETER

<tr>
    <td>{$cat_id}</td>
    <td>{$cat_title}</td>
    <td><a class="btn btn-danger" href="../resources/templates/back/delete_category.php?id={$cat_id}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>

DELIMETER;

echo $category;
    
}


    
}


function add_category(){
    
    if(isset($_POST['add_category'])){
        
        $cat_title = escape_string($_POST['cat_title']);
        
        $query = query("insert into categories (cat_title) values ('{$cat_title}')");
        confirm($query);
        
        set_message("Category Created");

    }
    
}



//***********************Admin Users***************************************//


function display_users(){
    
$query = "select * from users";
$users_query = query($query);
confirm($users_query);

while($row = fetch_array($users_query)){
    
$user_id = $row['user_id'];
$username = $row['username'];
$email = $row['email'];
$password = $row['password'];

$category = <<<DELIMETER

<tr>
    <td>{$user_id}</td>
    <td>{$username}</td>
    <td>{$email}</td>
    <td><a class="btn btn-danger" href="../../resources/templates/back/delete_user.php?id={$user_id}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>

DELIMETER;

echo $category;
    
}
    
}


function add_user() {
    
    if(isset($_POST['add_user'])){
        
        $username = escape_string($_POST['username']);
        $email = escape_string($_POST['email']);
        $password = escape_string($_POST['password']);
/*        $user_photo = escape_string($_FILES['file']['name']);
        $photo_temp = escape_string($_FILES['file']['tmp_name']);*/
        
        
        move_uploaded_file($photo_temp, UPLOAD_DIRECTORY.DS.$user_photo);
        
        $query = query("insert into users (username, email, password) values ('{$username}', '{$email}', '{$paswword}')");
        
        confirm($query);
        
        redirect("index.php?users");
        
        
        
    }
}




function get_reports(){
    
$query = query("select * from reports");

confirm($query);

while($row = fetch_array($query)){
        
$report = <<<DELIMETER

<tr>
    <td>{$row['report_id']}</td>
    <td>{$row['product_id']}</td>
    <td>{$row['order_id']}</td>
    <td>{$row['product_price']}</td>
    <td>{$row['product_title']}<br>
    <td>{$row['product_quantity']}</td>
    <td><a class="btn btn-danger" href="../../resources/templates/back/delete_report.php?id={$row['report_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>


DELIMETER;

echo $report;
        
    }
    
}
    






?>