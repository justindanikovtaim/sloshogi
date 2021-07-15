<?php

$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/

    $newChallenge = 'INSERT INTO gamerecord (moves, blackplayer, whiteplayer, status, creator)
     VALUES ( "", "' .$_COOKIE["current_user_cookie"].'", "'.$_POST["opponent"].'", "2", "' .$_COOKIE["current_user_cookie"].'");'; 

?> 
<!DOCTYPE html>
<head>
    <title>Slo Shogi Challenge</title>
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
 <br>
 <a href = "user_page.php">
     <img src = "images/return.png">
</a>

 </html>