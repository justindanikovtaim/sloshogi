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
<h1>NEW GAME</h1>
<div class='buttonRow'>
<a href = "new_challenge.html.php"><button class="bigMenuButton">&nbsp;友達をチャレンジ&nbsp;</button></a>
</div>
<br><br>
<div class='buttonRow'>
<a href = "create_open_game.html.php"><button class="bigMenuButton">オープン対局を作る</button></a>
</div>
<br><br>
<div class='buttonRow'>
<a  href = "join_game.html.php"><button class="bigMenuButton">オープン対局を探す</button></a>
</div>
<br>

<div class = "user">
    <h1>新着チャレンジ</h1>
<div id = "recievedChallenges"></div>
</div>

<div class = "user">
    <h1><?=$_COOKIE['current_user_cookie']?>のチャレンジ</h1>
<div id = "sentChallenges"></div>
</div>

<script src = "scripts/get_challenges.js"></script>

</body>
