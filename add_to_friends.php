<?php

require 'connect.php';
$userVar = $_COOKIE['current_user_cookie'];
$friendName = $_GET['name'];
$getFriendId = mysqli_query($link, "SELECT id FROM users WHERE username = '".$friendName."'");
$friendIdArray = mysqli_fetch_array($getFriendId);
$friendId = $friendIdArray['id'];

$query = "UPDATE users SET friends = CONCAT(friends, ',".$friendId."') WHERE username = '".$userVar."'";
?>
<!DOCTYPE html>
<head>
<link href="CSS/all_pages.css" rel="stylesheet">
<link href="CSS/friends_page.css" rel="stylesheet">
</head>
<body>
<a id = "backButton" href = "view_friend.php?friendName=<?=$friendName?>">≪</a>
<br><br>
<h3>
    <?php
    if(mysqli_query($link, $query)){
        echo "友達が追加されました！ Friend successfully added!";
    }else{
        echo "エラーが発生しました。やり直してください。<br>There was an error. Please try again";
    }
    ?>
</h3>
</body>