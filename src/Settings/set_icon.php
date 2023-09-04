<?php
$newIcon = $_GET['newIcon'];

require 'connect.php';

setcookie('icon', $newIcon, time() + (86400 * 365), "/");
mysqli_query($link, 'UPDATE users SET icon = "'.$newIcon.'" WHERE username = "'.$_COOKIE['current_user_cookie'].'"');
header("Location: settings.php");
 ?>