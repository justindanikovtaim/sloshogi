<?php 
$gameID = $_GET['id'];
require 'connect.php';
$result = mysqli_query($link, 'SELECT * FROM gamerecord WHERE id = '.$gameID); //get all the current info about the game
$row = mysqli_fetch_array($result);
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Slo Shogi</title>
        <link href="CSS/all_pages.css" rel="stylesheet">
        <link href="CSS/view_challenge.css" rel="stylesheet">
</head>

<body bgcolor="#f0e68c">

<a id = "backButton" href = "user_page.php">≪</a> 
<br><br>
<h2 class = "centered">Challenge From <?=$row["creator"]?>　からのチャレンジ</h2>
<h1>黒Black: <?=$row["blackplayer"]?> </h1>

<img src = "images/untouchedBoard.JPG" id = "boardPhoto">
<h1>白White: <?=$row["whiteplayer"]?></h1>

<div id = "accept">
<h2><a href = "accept_challenge.php?id=<?=$gameID?>">承認 Accept</a></h2>
<h2><a href = "decline_challenge.php?id=<?=$gameID?>">拒否 Decline</a></h2>
</div>

</body>

</html>
