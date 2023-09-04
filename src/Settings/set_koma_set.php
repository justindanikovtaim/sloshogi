<?php
$newKomaSet = $_GET['newKomaSet'];

require 'connect.php';

mysqli_query($link, 'UPDATE users SET komaSet = "'.$newKomaSet.'" WHERE username = "'.$_COOKIE['current_user_cookie'].'"');
header("Location: settings.php");
 ?>