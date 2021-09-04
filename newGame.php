<?php
$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/

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
    if($getOpponentArray['blackplayer'] == $_COOKIE['current_user_cookie']){

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

<a href = "new_challenge.html.php">Challenge a Friend</a>
<br>
<a href = "create_open_game.html.php">New Open Game</a>
<br>
<a  href = "join_game.html.php">Join Open Game</a>
<br>

<div class = "user">
    <h1>新着チャレンジ<br>Newly-Recieved Challenges</h1>
<div id = "recievedChallenges"></div>
</div>

<div class = "user">
    <h1><?=$_COOKIE['current_user_cookie']?>のチャレンジ　<br><?=$_COOKIE['current_user_cookie']?>'s Challenges</h1>
<div id = "sentChallenges"></div>
</div>

<script src = "scripts/get_challenges.js"></script>

</body>
