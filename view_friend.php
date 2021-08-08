<?php
$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
$getUserInfo = mysqli_query($link, "SELECT rating, record FROM users WHERE username = '".$_GET['friendName']."'");
$userInfoArray = mysqli_fetch_array($getUserInfo);

?>
<!DOCTYPE html>
<head>
<link href="CSS/all_pages.css" rel="stylesheet">
</head>
<body>
<a id = "backButton" href = "friends.php">≪</a>
<br>
<h1><?=$_GET['friendName']?>'s Profile</h1>
<h1>段級: <?=$userInfoArray['rating']?></h1>
<h1>勝敗レコード: <?=$userInfoArray['record']?> </h1>

</body>