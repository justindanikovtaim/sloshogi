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
<br>
<div id = "all">
<div id = "nameIconRating">
<h1 id = "userName"><?=$_GET['friendName']?></h1>
<h2 id = "rating">段級: <?=$userInfoArray['rating']?></h1>
<h2 id = "record">勝敗レコード: <?=$userInfoArray['record']?> </h1>
<div id="iconBox">
<img src= "images/icons/<?=$userInfoArray['icon']?>_icon.png" id = "userIcon">
</div>
</div>
</div>

</body>