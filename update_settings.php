<?php
require 'connect.php';

$newHitokoto = $_POST['hitokoto'];

 mysqli_query($link, 'UPDATE users SET hitokoto = "'.$newHitokoto.'" WHERE username = "'.$_COOKIE['current_user_cookie'].'"');

 header('location: user_page.php');
?>