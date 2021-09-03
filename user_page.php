<?php
session_start();

$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
$getCurrentGameId = mysqli_query($link, "SELECT id FROM gamerecord WHERE status = 2 AND 
( blackplayer = '".$_COOKIE['current_user_cookie'] ."' OR whiteplayer = '".$_COOKIE['current_user_cookie'] ."')" );
$currentGameIdArray =  [];
while($row = mysqli_fetch_array($getCurrentGameId)){
    array_push($currentGameIdArray, $row['id']);//add each gameid related to the user to an array
}
$opponentNameArray = [];
for($i = 0; $i < sizeof($currentGameIdArray); $i++){
    $getOpponent = mysqli_query($link, "SELECT blackplayer, whiteplayer FROM gamerecord WHERE id = '".$currentGameIdArray[$i]."'");
    $getOpponentArray = mysqli_fetch_array($getOpponent);
    if($getOpponentArray['blackplayer'] == $_COOKIE['current_user_cookie']){

        array_push($opponentNameArray, $getOpponentArray['whiteplayer']);
    }else{
        array_push($opponentNameArray, $getOpponentArray['blackplayer']);
    }
}

$getNewChallenges = mysqli_query($link, "SELECT id FROM gamerecord WHERE status = 1 AND creator != '".$_COOKIE['current_user_cookie']."' AND
( blackplayer = '".$_COOKIE['current_user_cookie'] ."' OR whiteplayer = '".$_COOKIE['current_user_cookie'] ."')" );
//get challenges that the user didn't create themselves
$challengesIdArray =  [];
while($row = mysqli_fetch_array($getNewChallenges)){
    array_push($challengesIdArray, $row['id']);//add each gameid related to the user to an array
}
$challengingOpponentArray = [];
for($i = 0; $i < sizeof($challengesIdArray); $i++){
    $getOpponent = mysqli_query($link, "SELECT blackplayer, whiteplayer FROM gamerecord WHERE id = '".$challengesIdArray[$i]."'");
    $getOpponentArray = mysqli_fetch_array($getOpponent);
    if($getOpponentArray['blackplayer'] == $_COOKIE['current_user_cookie']){

        array_push($challengingOpponentArray, $getOpponentArray['whiteplayer']);
    }else{
        array_push($challengingOpponentArray, $getOpponentArray['blackplayer']);
    }
}


$getPastGameId = mysqli_query($link, "SELECT id FROM gamerecord WHERE status = 3 AND 
( blackplayer = '".$_COOKIE['current_user_cookie'] ."' OR whiteplayer = '".$_COOKIE['current_user_cookie'] ."')" );
$pastGameIdArray =  [];
while($row = mysqli_fetch_array($getPastGameId)){
    array_push($pastGameIdArray, $row['id']);//add each gameid related to the user to an array
}
$pastOpponentNameArray = [];
for($i = 0; $i < sizeof($pastGameIdArray); $i++){
    $getOpponent = mysqli_query($link, "SELECT blackplayer, whiteplayer FROM gamerecord WHERE id = '".$pastGameIdArray[$i]."'");
    $getOpponentArray = mysqli_fetch_array($getOpponent);
    if($getOpponentArray['blackplayer'] == $_COOKIE['current_user_cookie']){

        array_push($pastOpponentNameArray, $getOpponentArray['whiteplayer']);
    }else{
        array_push($pastOpponentNameArray, $getOpponentArray['blackplayer']);
    }
}

$getUserInfo = mysqli_query($link, "SELECT * FROM users WHERE username = '".$_COOKIE['current_user_cookie']."'");
$userInfoArray = mysqli_fetch_array($getUserInfo);
?>

<!DOCTYPE HTML>
<head>
    <script>
        let currentGameIdArray = <?php echo json_encode($currentGameIdArray) ; ?>;
        let currentGameOpponentArray = <?php echo json_encode($opponentNameArray) ; ?>;
        let newChallengesArray = <?php echo json_encode($challengesIdArray) ; ?>;
        let challengesOpponentArray = <?php echo json_encode($challengingOpponentArray) ; ?>;
        let pastGameIdArray = <?php echo json_encode($pastGameIdArray) ; ?>;
        let pastGameOpponentArray = <?php echo json_encode($pastOpponentNameArray) ; ?>;
    
    </script>

    <link href="CSS/all_pages.css" rel="stylesheet">
 </head>
 <body>
<h1><?php echo $_COOKIE['current_user_cookie']."'s";?> Page</h1>
<br>
<h2>段級: <?=$userInfoArray['rating']?></h2>
<h2>勝敗レコード: <?=$userInfoArray['record']?> </h2>


<div class="user"> 
<h3>Current Games</h3>
<br>

<div id = "allGames"></div>
</div>

<h3><a href = "newGame.php">New Game</a></h3>
<h3><a href = "friends.php">Friends</a></h3>

<div class ="user">
    <h3>New Challenges</h3>
    <br>

<div id = "newChallenges"></div>
</div>


<div class="user">
<h3>Finished Games</h3>
<div id = "finishedGames"></div>
</div>
<a href = "logout.php" id = logoutButton>ログアウトLog Out</a>

<script src = "scripts/get_games.js"></script>
    </body>
