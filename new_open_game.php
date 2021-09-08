<?php

require 'connect.php';

    $userColor = $_POST["userColor"];

    $newChallenge = 'INSERT INTO gamerecord (moves, '.$_POST["userColor"].', status, creator)
     VALUES ( "", "' .$_COOKIE["current_user_cookie"].'", "1", "' .$_COOKIE["current_user_cookie"].'");'; 

?> 
<!DOCTYPE html>
<head>
    <title>Slo Shogi New Game</title>
</head>
<h2>
<a id = "backButton" href = "newGame.php">≪</a>

<?php
    if(mysqli_query($link, $newChallenge)){
        echo "Open game created";
    } else{
        echo "ERROR: Not able to execute $newChallenge. " . mysqli_error($link);
    }
 ?>
 </h2>


 </html>