<?php
session_start();
require 'connect.php';

$enteredPW = htmlspecialchars($_POST['pw']);
$currentUser = htmlspecialchars($_POST['userData']);

$verifyPWQuery = mysqli_query($link, "SELECT pass FROM users WHERE BINARY username = '".$currentUser ."'");

$verifyPW = mysqli_fetch_array($verifyPWQuery, MYSQLI_NUM); //make numeric array

if(!password_verify($enteredPW, $verifyPW[0]) && $enteredPW != $verifyPW[0]){
    header('Location: /sloshogi/login_error.html');
    die("couldn't be found");
}else{
    $getUserIcon = mysqli_query($link, "SELECT icon FROM users WHERE username = '".$currentUser ."'");//get the set icon
    $icon = mysqli_fetch_array($getUserIcon);
    
    setcookie('current_user_cookie', $currentUser, time() + (86400 * 365), "/"); // 86400 = 1 day
    setcookie('icon', $icon['icon'], time() + (86400 * 365), "/");
    
    header('Location: user_page.php');
}


?>
