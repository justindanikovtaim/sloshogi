<?php
session_start();
$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/

$enteredPW = htmlspecialchars($_POST['pw']);
$currentUser = htmlspecialchars($_POST['userData']);

$verifyPWQuery = mysqli_query($link, "SELECT pass FROM users WHERE username = '".$currentUser ."'");

$verifyPW = mysqli_fetch_array($verifyPWQuery, MYSQLI_NUM); //make numeric array

if($enteredPW != $verifyPW[0]){
    header('Location: /sloshogi/index.html.php');
    die("couldn't be found");
}


setcookie('current_user_cookie', $currentUser, time() + (86400 * 30), "/"); // 86400 = 1 day
header('Location: user_page.php');
?>
