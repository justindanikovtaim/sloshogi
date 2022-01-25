<?php
session_start();

require 'connect.php';


$getPastGameId = mysqli_query($link, "SELECT id FROM gamerecord WHERE (status = 3 
OR (status = 4 AND winner = '".$_COOKIE['current_user_cookie']."' ) 
OR (status = 5 AND winner != '".$_COOKIE['current_user_cookie']."') ) 
AND 
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

        let pastGameIdArray = <?php echo json_encode($pastGameIdArray) ; ?>;
        let pastGameOpponentArray = <?php echo json_encode($pastOpponentNameArray) ; ?>;

    </script>

    <link href="CSS/all_pages.css" rel="stylesheet">
    <link href="CSS/user_page.css" rel="stylesheet">
 </head>
 <body>
 <a id = "backButton" href = "user_page.php">≪</a>
 <br><br>

<div id = "all">
    <div id = "nameIconRating">
    <h1 id = "userName"><?=$_COOKIE['current_user_cookie']?></h1>
    <h2 id = "rating">段級: ?</h2>
    <h2 id = "record"><?=$userInfoArray['record']?>&nbsp&nbsp </h2>
    <p id="hitokotoInput">"<?=$userInfoArray['hitokoto']?>"</p>
    <a href = "settings.php"id = "settings" >設定 Settings</a>
    <div id="iconBox">
    <img src= "images/icons/<?=$_COOKIE['icon']?>_icon.png" id = "userIcon">
    </div>
    </div>


<div class="user">
<h3>Finished Games</h3>
<div id = "finishedGames"></div>
</div>
<br>
<h1><a href="feedback_form.php?src=user_page&id=finishedGames">バグ報告・Report a bug</a></h1>
<h1><a href = "slo_tsumeshogi.php">詰将棋（β版）</a></h1>
<h1><a href = "logout.php" id = "logoutButton">ログアウトLog Out</a></h1>

</div>
<script src = "scripts/get_finished_games.js"></script>
    </body>
