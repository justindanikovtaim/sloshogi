<?php
if($_SERVER['REQUEST_URI'] != '/sloshogi/verify_login.php' && $_SERVER['REQUEST_URI'] != '/sloshogi/email_temp_pw.php' ){ //*****UPDATE******** */
    if(!isset($_COOKIE['current_user_cookie'])){
       header('location: index.php');
        die();
    }
}
$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
if(mysqli_connect_errno()){
    header('location: db_error.html');
    die();
}
?>