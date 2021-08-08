<?php

$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/

    $userColor = $_POST["userColor"];

    $newChallenge = 'INSERT INTO gamerecord (moves, '.$_POST["userColor"].', status, creator)
     VALUES ( "", "' .$_COOKIE["current_user_cookie"].'", "1", "' .$_COOKIE["current_user_cookie"].'");'; 

?> 
<!DOCTYPE html>
<head>
    <title>Slo Shogi New Game</title>
</head>
<h2>
<?php
    if(mysqli_query($link, $newChallenge)){
        echo "Challenge Sent!";
    } else{
        echo "ERROR: Not able to execute $newChallenge. " . mysqli_error($link);
    }
 ?>
 </h2>


 </html>