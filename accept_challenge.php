<?php

require 'connect.php';
$getActiveGames = mysqli_query($link, "SELECT id FROM gamerecord WHERE 
(blackplayer = '".$_COOKIE['current_user_cookie']."' OR whiteplayer = '".$_COOKIE['current_user_cookie']."')
AND (status = 1 OR status = 2)");

$activeGameCount = 0;
while($sqlArrayHolder = mysqli_fetch_array($getActiveGames)){//this loop goes though the user's active games and increments the counter for each
   $activeGameCount ++; 
}

if($activeGameCount < 5){



if(mysqli_query($link,"UPDATE gamerecord SET status = 2 WHERE id = '".$_GET['id']."'")){
    header('Location: gameboard.php?id='.$_GET['id']);
}else{
    echo "There was an error. Please try again";
}
}else{
    echo "既に５つの対戦に参加しているため、新しい対戦に参加することはできません<br> You already have 5 active games/challenges. You cannot join a new game.";

}   


?> 
<!DOCTYPE html>
<head>
    <title>Slo Shogi Challenge</title>
    <link href="CSS/all_pages.css" rel="stylesheet">
</head>
<body>
<a id = "backButton" href = "view_challenge.php?id=<?=$_GET['id']?>">≪</a>

</body>
 </html>