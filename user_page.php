<?php
session_start();

$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
$getCurrentGameId = mysqli_query($link, "SELECT id FROM gamerecord WHERE status = 2 AND 
( blackplayer = '".$_COOKIE['current_user_cookie'] ."' OR whiteplayer = '".$_COOKIE['current_user_cookie'] ."')" );
$currentGameIdArray =  [];
while($row = mysqli_fetch_array($getCurrentGameId)){
    array_push($currentGameIdArray, $row['id']);//add each gameid related to the user to an array
}

$getPastGameId = mysqli_query($link, "SELECT id FROM gamerecord WHERE status = 3 AND 
( blackplayer = '".$_COOKIE['current_user_cookie'] ."' OR whiteplayer = '".$_COOKIE['current_user_cookie'] ."')" );
$pastGameIdArray =  [];
while($row = mysqli_fetch_array($getPastGameId)){
    array_push($pastGameIdArray, $row['id']);//add each gameid related to the user to an array
}
$getUserInfo = mysqli_query($link, "SELECT * FROM users WHERE username = '".$_COOKIE['current_user_cookie']."'");
$userInfoArray = mysqli_fetch_array($getUserInfo);
?>

<!DOCTYPE HTML>
<head>
    <script>
        var currentGameIdArray = <?php echo json_encode($currentGameIdArray) ; ?>;
        let pastGameIdArray = <?php echo json_encode($pastGameIdArray) ; ?>;
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

<div class="user">
<h3>Friends</h3>
<!--<a  href = "join_game.html.php">Find an Open Game</a>
<br> -->
<a href = "new_challenge.html.php">Challenge a Friend</a>
<br>
<a href = "#">Add New Friends</a>
<!--<a href = "create_open_game.html.php">Create a New Open Game</a>
<br>-->
</div>

<div class="user">
<h3>Finished Games</h3>
<div id = "finishedGames"></div>
</div>
    </body>
    <script src = "scripts/get_games.js"></script>
