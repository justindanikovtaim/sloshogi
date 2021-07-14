<?php
session_start();

$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
$getGameId = mysqli_query($link, "SELECT id FROM gamerecord WHERE  blackplayer = '".$_COOKIE['current_user_cookie'] ."' OR whiteplayer = '".$_COOKIE['current_user_cookie'] ."'" );
$gameIdArray =  [];
while($row = mysqli_fetch_array($getGameId)){
    array_push($gameIdArray, $row['id']);//add each gameid related to the user to an array
}
?>

<!DOCTYPE HTML>
<head>
    <script>
        var gameIdArray = <?php echo json_encode($gameIdArray) ; ?>;
    </script>
    <link href="CSS/all_pages.css" rel="stylesheet">
 </head>
 <body>
<h1><?php echo $_COOKIE['current_user_cookie']."'s";?> Page</h1>
<br>
<!--<a  href = "join_game.html.php">Find an Open Game</a>
<br> -->
<a href = "new_challenge.html.php">Challenge a Friend</a>
<br>
<!--<a href = "create_open_game.html.php">Create a New Open Game</a>
<br>-->

<div class="user"> 
<h3>Current Games</h3>
<br>

<div id = "allGames"></div>
</div>

<div class="user">
<h3>Friends</h3>
</div>

    </body>
    <script src = "scripts/get_games.js"></script>
