<?php
require 'connect.php';
$getUserInfo = mysqli_query($link, "SELECT rating, record, icon FROM users WHERE username = '".$_GET['friendName']."'");
$userInfoArray = mysqli_fetch_array($getUserInfo);

?>
<!DOCTYPE html>
<head>
<link href="CSS/all_pages.css" rel="stylesheet">
<link href="CSS/user_page.css" rel="stylesheet">

</head>
<body>
<a id = "backButton" href = "friends.php">≪</a>
<br>
<div id = "nameIconRating">
<h1><?=$_GET['friendName']?></h1>
<h2 class = "floatLeft">段級: <?=$userInfoArray['rating']?></h1>
<h2 class = "floatLeft">勝敗レコード: <?=$userInfoArray['record']?> </h1>
</div>
<img src= "images/icons/<?=$userInfoArray['icon']?>_icon.png" id = "userIcon">
</body>