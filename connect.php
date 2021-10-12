<?php
if(!isset($_COOKIE['current_user_cookie'])){
    header('location: index.php');
    die();
}
$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
?>