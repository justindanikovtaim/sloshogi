<?php

$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/

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
    if(mysqli_query($link, $joinGameCommand)){
        //upon success, redirect to the gameboard page
       echo "<script>location.href='gameboard.php?id=".$_GET['id']."';</script>";
    } else{
        //otherwise, redirect to the previous page
        echo "ERROR: Not able to execute $joinGameCommand. " . mysqli_error($link);
        echo "<script>location.href='join_game.html.php';</script>";
    }
 ?>
 </h1>

</body>
 </html>
