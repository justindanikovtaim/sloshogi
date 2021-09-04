<?php

$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
    if($_POST["userColor"] == "blackplayer"){
        $blackPlayer = $_COOKIE["current_user_cookie"];
        $whitePlayer = $_POST["opponent"];
    }else{
        $whitePlayer = $_COOKIE["current_user_cookie"];
        $blackPlayer = $_POST["opponent"];
    }

    $newChallenge = 'INSERT INTO gamerecord (moves, blackplayer, whiteplayer, status, creator)
     VALUES ( "", "' .$blackPlayer.'", "'.$whitePlayer.'", "1", "' .$_COOKIE["current_user_cookie"].'");'; 

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
    if(mysqli_query($link, $newChallenge)){
        echo "Challenge Sent!";
    } else{
        echo "ERROR: Not able to execute $newChallenge. " . mysqli_error($link);
    }
 ?>
 </h1>

</body>
 </html>