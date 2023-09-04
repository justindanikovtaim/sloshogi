<?php
require 'connect.php';

$getNewChallenges = mysqli_query($link, "SELECT id FROM gamerecord WHERE status = 1 AND creator = '".$_COOKIE['current_user_cookie']."' AND
( blackplayer = '".$_COOKIE['current_user_cookie'] ."' OR whiteplayer = '".$_COOKIE['current_user_cookie'] ."')" );
//get challenges that the user created themselves
$sentChallengesIdArray =  [];
while($row = mysqli_fetch_array($getNewChallenges)){
    array_push($sentChallengesIdArray, $row['id']);//add each gameid related to the user to an array
}
$sentChallengingOpponentArray = [];
for($i = 0; $i < sizeof($sentChallengesIdArray); $i++){
    $getOpponent = mysqli_query($link, "SELECT blackplayer, whiteplayer FROM gamerecord WHERE id = '".$sentChallengesIdArray[$i]."'");
    $getOpponentArray = mysqli_fetch_array($getOpponent);
    if($getOpponentArray['blackplayer'] == null || $getOpponentArray['whiteplayer'] == null){
        //if one of the players is null, it's an open challenge
        array_push($sentChallengingOpponentArray, "? Open Game");
    }else if($getOpponentArray['blackplayer'] == $_COOKIE['current_user_cookie']){

        array_push($sentChallengingOpponentArray, $getOpponentArray['whiteplayer']);
    }else{
        array_push($sentChallengingOpponentArray, $getOpponentArray['blackplayer']);
    }
}

$getNewChallenges = mysqli_query($link, "SELECT id FROM gamerecord WHERE status = 1 AND creator != '".$_COOKIE['current_user_cookie']."' AND
( blackplayer = '".$_COOKIE['current_user_cookie'] ."' OR whiteplayer = '".$_COOKIE['current_user_cookie'] ."')" );
//get challenges that the user didn't create themselves
$recievedChallengesIdArray =  [];
while($row = mysqli_fetch_array($getNewChallenges)){
    array_push($recievedChallengesIdArray, $row['id']);//add each gameid related to the user to an array
}
$recievedChallengingOpponentArray = [];
for($i = 0; $i < sizeof($recievedChallengesIdArray); $i++){
    $getOpponent = mysqli_query($link, "SELECT blackplayer, whiteplayer FROM gamerecord WHERE id = '".$recievedChallengesIdArray[$i]."'");
    $getOpponentArray = mysqli_fetch_array($getOpponent);
    if($getOpponentArray['blackplayer'] == $_COOKIE['current_user_cookie']){

        array_push($recievedChallengingOpponentArray, $getOpponentArray['whiteplayer']);
    }else{
        array_push($recievedChallengingOpponentArray, $getOpponentArray['blackplayer']);
    }
}

$getOpenGameId = mysqli_query($link, "SELECT id FROM gamerecord WHERE status = 1 AND creator != '".$_COOKIE['current_user_cookie'] ."'" );
$openGameIdArray =  [];
while($row = mysqli_fetch_array($getOpenGameId)){
    array_push($openGameIdArray, $row['id']);//add each open game id that user didn't make
}
$opponentNameArray = [];
for($i = 0; $i < sizeof($openGameIdArray); $i++){
    $getOpponent = mysqli_query($link, "SELECT creator FROM gamerecord WHERE id = '".$openGameIdArray[$i]."'");
    $getOpponentArray = mysqli_fetch_array($getOpponent);
    $getOpRating = mysqli_query($link, "SELECT rating FROM users WHERE username = '".$getOpponentArray['creator']."'");
    $opRating = mysqli_fetch_array($getOpRating);
    array_push($opponentNameArray, $getOpponentArray['creator']."(".$opRating['rating'].")");
}

?>

<!DOCTYPE html>
<head>
<link href="CSS/all_pages.css" rel="stylesheet">
<link href='CSS/user_page.css' rel='stylesheet'>

<script>
        let sentChallengesArray = <?php echo json_encode($sentChallengesIdArray) ; ?>;
        let sentChallengesOpponentArray = <?php echo json_encode($sentChallengingOpponentArray) ; ?>;
        let recievedChallengesArray = <?php echo json_encode($recievedChallengesIdArray) ; ?>;
        let recievedChallengesOpponentArray = <?php echo json_encode($recievedChallengingOpponentArray) ; ?>;

    </script>

</head>
<body>
<a id = "backButton" href = "user_page.php">≪</a>
<h1>新規対局</h1>
<div class='buttonRow'>
<a href = "new_challenge.html.php"><button class="bigMenuButton">&nbsp;友達と対局&nbsp;</button></a>
</div>
<br><br>
<div class='buttonRow'>
<a href = "create_open_game.html.php"><button class="bigMenuButton">オープン対局を作る</button></a>
</div>
<br><br>

<div class = "user">
    <h1>オープン対局</h1>
    <div id = "drawOpenGames"></div>
</div>

<div class = "user">
    <h1>承認待ち対局</h1>
<div id = "sentChallenges"></div>
</div>

<script src = "scripts/get_challenges.js"></script>

</body>
<script>
        let getOpenGamesArray = <?php echo json_encode($openGameIdArray) ; ?>;
        let currentGameOpponentArray = <?php echo json_encode($opponentNameArray) ; ?>;
    let openGamesArray = [];

    for(i = 0; i < getOpenGamesArray.length; i ++){
    openGamesArray[i] = document.createElement("a");
    openGamesArray[i].href = "join_open_game.php?id=" + getOpenGamesArray[i];
    openGamesArray[i].innerHTML = "vs. " + currentGameOpponentArray[i];
    document.getElementById("drawOpenGames").appendChild(openGamesArray[i]);
    let lineBreak = document.createElement("br");
    document.getElementById("drawOpenGames").appendChild(lineBreak);
}
    </script>
