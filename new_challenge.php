<?php

require 'connect.php';


if($_POST["userColor"] == "blackplayer"){
        $blackPlayer = $_COOKIE["current_user_cookie"];
        $whitePlayer = $_POST["opponent"];
    }else{
        $whitePlayer = $_COOKIE["current_user_cookie"];
        $blackPlayer = $_POST["opponent"];
    }

    $newChallenge = 'INSERT INTO gamerecord (moves, blackplayer, whiteplayer, status, creator)
     VALUES ( "", "' .$blackPlayer.'", "'.$whitePlayer.'", "1", "' .$_COOKIE["current_user_cookie"].'");';

    $getActiveGames = mysqli_query($link, "SELECT id FROM gamerecord WHERE 
    (blackplayer = '".$_COOKIE['current_user_cookie']."' OR whiteplayer = '".$_COOKIE['current_user_cookie']."')
    AND (status = 1 OR status = 2)");

    $activeGameCount = 0;
   while($sqlArrayHolder = mysqli_fetch_array($getActiveGames)){//this loop goes though the user's active games and increments the counter for each
       $activeGameCount ++; 
   }

?> 
<!DOCTYPE html>
<head>
    <title>Slo Shogi Challenge</title>
    <link href="CSS/all_pages.css" rel="stylesheet">
</head>
<body>
<a id = "backButton" href = "newGame.php">≪</a>
<h1>
<?php
    if($activeGameCount < 5){

    if(mysqli_query($link, $newChallenge)){
        echo "Challenge Sent!";
    } else{
        echo "ERROR: Not able to execute $newChallenge. " . mysqli_error($link);
    }
}else{
    echo "既に５つの対戦に参加しているため、新しい対戦を作ることはできません<br> You already have 5 active games/challenges. You cannot create a new challenge.";
}

 ?>
 </h1>

</body>
 </html>