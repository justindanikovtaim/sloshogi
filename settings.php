<?php
$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
$getUserInfo = mysqli_query($link, "SELECT * FROM users WHERE username = '".$_COOKIE['current_user_cookie']."'");
$userInfoArray = mysqli_fetch_array($getUserInfo);

?>

<!DOCTYPE HTML>
<head>
    <link href="CSS/all_pages.css" rel="stylesheet">
    <link href="CSS/user_page.css" rel="stylesheet">
 </head>
 <body>
 <a id = "backButton" href = "user_page.php">≪</a>
 <br>
 <br>

     <div id = "nameIconRating">
<h1 class = "floatLeft"><?=$_COOKIE['current_user_cookie']?> </h1>
<h2 class = "floatLeft">段級: <?=$userInfoArray['rating']?></h2>
<h2 class = "floatLeft">勝敗レコード: <?=$userInfoArray['record']?></h2>
</div>
<a href ="update_icon.php"><img src= "images/icons/<?=$_COOKIE['icon']?>_icon.png" id = "userIcon"></a>

<!--need to add password reset field here -->

<h1><a href = "logout.php" id = logoutButton>ログアウトLog Out</a></h1>