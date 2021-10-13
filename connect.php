<?php
if($_SERVER['REQUEST_URI'] != '/sloshogi/verify_login.php'){ //*****UPDATE******** */
    if(!isset($_COOKIE['current_user_cookie'])){
       header('location: index.php');
        die();
    }
}
$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
?>