<?php

require 'connect.php';

$getActiveGames = mysqli_query($link, "SELECT id FROM gamerecord WHERE 
(blackplayer = '".$_COOKIE['current_user_cookie']."' OR whiteplayer = '".$_COOKIE['current_user_cookie']."')
AND (status = 1 OR status = 2)");

$activeGameCount = 0;
while($sqlArrayHolder = mysqli_fetch_array($getActiveGames)){//this loop goes though the user's active games and increments the counter for each
   $activeGameCount ++; 
}

    if(mysqli_query($link, "SELECT blackplayer FROM gamerecord WHERE id = '".$_GET['id']."'") == "NULL"){
        //if blackplayer is null, the opponent is the other color, so the user should become blackplayer
        $joinGameCommand = 'UPDATE gamerecord SET status = 2, blackplayer = "'.$_COOKIE["current_user_cookie"].'"'; 
    }else{
        $joinGameCommand = 'UPDATE gamerecord SET status = 2, whiteplayer = "'.$_COOKIE["current_user_cookie"].'"'; 
    }


?> 
<!DOCTYPE html>
<head>
    <link href="CSS/all_pages.css" rel="stylesheet">
</head>

<h1>
<?php
if($activeGameCount < 5){

    if(mysqli_query($link, $joinGameCommand)){
        //upon success, redirect to the gameboard page
       echo "<script>location.href='gameboard.php?id=".$_GET['id']."';</script>";
    } else{
        //otherwise, redirect to the previous page
        echo "ERROR: Not able to execute $joinGameCommand. " . mysqli_error($link);
        echo "<script>location.href='join_game.html.php';</script>";
    }
}else{
    echo "既に５つの対戦に参加しているため、新しい対戦に参加することはできません<br> You already have 5 active games/challenges. You cannot join a new game.";
}
 ?>
 </h1>

</body>
 </html>
