<?php

require 'connect.php';

    $userColor = $_POST["userColor"];

    $newChallenge = 'INSERT INTO gamerecord (moves, '.$_POST["userColor"].', status, creator)
     VALUES ( "", "' .$_COOKIE["current_user_cookie"].'", "1", "' .$_COOKIE["current_user_cookie"].'");'; 
     
    $getActiveGames = mysqli_query($link, "SELECT id FROM gamerecord WHERE 
    (blackplayer = '".$_COOKIE['current_user_cookie']."' OR whiteplayer = '".$_COOKIE['current_user_cookie']."')
    AND (status = 1 OR status = 2)");

    $activeGameCount = 0;
   while($sqlArrayHolder = mysqli_fetch_array($getActiveGames)){//this loop goes though the user's active games and increments the counter for each
       $activeGameCount ++; 
   }

   $getUserLevel = mysqli_query($link, "SELECT user_level FROM users WHERE username = '".$_COOKIE['current_user_cookie']."'");
$userLevel = mysqli_fetch_array($getUserLevel);

?> 
<!DOCTYPE html>
<head>
    <title>Slo Shogi New Game</title>
    <link href="CSS/all_pages.css" rel="stylesheet">

</head>
<h2>
<a id = "backButton" href = "newGame.php">≪</a>
<br><br>
<?php
    if($activeGameCount < 5 || $userLevel['user_level'] > 0){

    if(mysqli_query($link, $newChallenge)){
        echo "Open game created";
        echo $activeGameCount;
    } else{
        echo "ERROR: Not able to execute $newChallenge. " . mysqli_error($link);
    }

}else{
    echo "既に５つの対戦に参加しているため、新しい対戦を作ることはできません<br> You already have 5 active games/challenges. You cannot create a new game.";
}
 ?>
 </h2>


 </html>