<?php
session_start();

require 'connect.php';
$getOpenGameId = mysqli_query($link, "SELECT id FROM gamerecord WHERE status = 1 AND creator != '".$_COOKIE['current_user_cookie'] ."'" );
$openGameIdArray =  [];
while($row = mysqli_fetch_array($getOpenGameId)){
    array_push($openGameIdArray, $row['id']);//add each open game id that user didn't make
}
$opponentNameArray = [];
for($i = 0; $i < sizeof($openGameIdArray); $i++){
    $getOpponent = mysqli_query($link, "SELECT creator FROM gamerecord WHERE id = '".$openGameIdArray[$i]."'");
    $getOpponentArray = mysqli_fetch_array($getOpponent);
    array_push($opponentNameArray, $getOpponentArray['creator']);
}
?>

<!DOCTYPE html>
<head>
<link href="CSS/all_pages.css" rel="stylesheet">
</head>
<body>
<a id = "backButton" href = "newGame.php">≪</a>
<h1>Open Games</h1>
<div id = "drawOpenGames"></div>


</body>
<script>
        let getOpenGamesArray = <?php echo json_encode($openGameIdArray) ; ?>;
        let currentGameOpponentArray = <?php echo json_encode($opponentNameArray) ; ?>;
    let openGamesArray = [];

    for(i = 0; i < getOpenGamesArray.length; i ++){
    openGamesArray[i] = document.createElement("a");
    openGamesArray[i].href = "join_open_game.php?id=" + getOpenGamesArray[i];
    openGamesArray[i].innerHTML = "SLO" + getOpenGamesArray[i] +" vs. " + currentGameOpponentArray[i];
    document.getElementById("drawOpenGames").appendChild(openGamesArray[i]);
    let lineBreak = document.createElement("br");
    document.getElementById("drawOpenGames").appendChild(lineBreak);
}
    </script>