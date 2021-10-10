<?php
require 'connect.php';
$userVar = $_COOKIE['current_user_cookie'];

$getUserInfo = mysqli_query($link, "SELECT rating, record, icon, id FROM users WHERE username = '".$_GET['friendName']."'");
$userInfoArray = mysqli_fetch_array($getUserInfo);

//get the user's friend list to see whether the curerntly-viewed player is on it or not
$getFriends =  mysqli_query($link, 'SELECT friends, id FROM users WHERE username = "'.$userVar.'"'); 
$friendsArray = mysqli_fetch_array($getFriends);
$userId = $friendsArray['id'];
$friendIds = explode(',', $friendsArray['friends']);//should separate the friend list by commas

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
<?php
if(array_search($userInfoArray['id'], $friendIds) === false    // using === false is important https://stackoverflow.com/questions/2581619/php-what-does-array-search-return-if-nothing-was-found
&& $userInfoArray['id'] !== $userId){
    //if the currently viewed friend isn't in the user's friend list and it isn't your own page
    echo "<a href='add_to_friends.php?name=".$_GET['friendName']."'>友達に追加　Add to friends</a>";
}

?>
</body>