<?php 
require 'connect.php';

session_start();
$gameID = $_GET['id'];
$result = mysqli_query($link, 'SELECT * FROM gamerecord WHERE id = '.$gameID); //get all the current from moves
$temparray = array();
$row = mysqli_fetch_array($result);
array_push($temparray,$row["moves"], $row["blackplayer"], $row["whiteplayer"], 
$row["reservation1"], $row["reservation2"], $row["reservation3"], $row["status"], $row["winner"], $_COOKIE['current_user_cookie']); 

if($row['blackplayer'] == $_COOKIE['current_user_cookie']){
    //get the opponent's username
    $opponentName = $row['whiteplayer'];
}else{
    $opponentName = $row['blackplayer'];
}

$getUserInfo = mysqli_query($link, 'SELECT rating, icon, username FROM users WHERE username = "'.$opponentName.'"');
$opInfo = mysqli_fetch_array($getUserInfo);

$getUserInfo = mysqli_query($link, 'SELECT rating, icon, username FROM users WHERE username = "'.$_COOKIE['current_user_cookie'].'"');
$userInfo = mysqli_fetch_array($getUserInfo);

?>

<!DOCTYPE HTML>
<html onload ="drawBoard()">
<head>
    <meta charset="utf-8">
    <title>Slo Shogi</title>
    
    <link href="CSS/Gameboard_style_sheet.css" rel="stylesheet">
    <link href="CSS/all_pages.css" rel="stylesheet">

</head>

<body>
<div id = "wholeBoard">
<div id ="boardColor">
<a href = "user_page.php"id = "backButton">≪</a>

<div  id = "opInfo">
<a href = "view_friend.php?friendName=<?=$opInfo['username']?>">
    <div id="opIconBox">
<img src="images/icons/<?=$opInfo['icon']?>_icon.png" id = "opIcon">
</div>
<div id="opNameBox">
<h4 id="opName"><?=$opponentName?></h4>
</div>
</a>
</div>




    <div id = "whiteMochigoma"></div>
    <div class="boardNum" id = "topNumber1">9</div>
    <div class="boardNum" id="topNumber2">8</div>
    <div class="boardNum" id="topNumber3">7</div>
    <div class="boardNum" id="topNumber4">6</div>
    <div class="boardNum" id="topNumber5">5</div>
    <div class="boardNum" id="topNumber6">4</div>
    <div class="boardNum" id="topNumber7">3</div>
    <div class="boardNum" id="topNumber8">2</div>
    <div class="boardNum" id="topNumber9">1</div>

    <div class="boardKanji" id="kanji9">一</div>
    <div class="boardKanji" id="kanji8">二</div>
    <div class="boardKanji" id="kanji7">三</div>
    <div class="boardKanji" id="kanji6">四</div>
    <div class="boardKanji" id="kanji5">五</div>
    <div class="boardKanji" id="kanji4">六</div>
    <div class="boardKanji" id="kanji3">七</div>
    <div class="boardKanji" id="kanji2">八</div>
    <div class="boardKanji" id="kanji1">九</div>


    <div id = "board"></div>
    <div id = "blackMochigoma"></div>
 
    <div id ="undo" onClick = "window.location.reload()"><img src ="images/undo_button.png" id="undoImg"></div>
</div>

<div  id = "userInfo">
    <div id="userIconBox">
<img src="images/icons/<?=$userInfo['icon']?>_icon.png" id = "userIcon">
</div>
<div id="userNameBox">
<h4 id="userName"><?=$_COOKIE['current_user_cookie']?></h4>
</div>
</div>

<div id="menuBox" onclick = "showMenu()"><img src="images/menu_button.png"  id = "menuButton"></div>

    <div class = "popupMenu" id="popupMenuId">
    <a href="#">チャット表示・View Chat</a>
    <a href="#" onClick = "resign()">校了・Resign</a>
    <a href="#">バッグ報告・Report a bug</a>
</div>

    <div id = "promptBox">
 <h3 id = "playerPrompt"></h3> 
</div>

 <div id = "skipButtons">
 <button class = "skipButton" id = "fullBack" onClick = "skipBack()">≪</button>
 <button class = "skipButton" id = "oneBack" onClick = "stepBack()"> < </button>
 <button class = "skipButton" id = "oneForward" onClick = "stepForward()"> > </button>
 <button class = "skipButton" id = "fullForward" onClick = "skipForward()">≫</button>
 
</div>



<div id = "resButtons">
    <div id="resTextBox">
        <h3 id ="resText">自動指し予約　Move Reservation</h3>
</div>
    <div id = "resBox1">
<a href="move_reservation.php?id=<?=$gameID?>&resBox=1" ><img src = images/reservation/res_1_grey.png id = "resButton1"></a>
</div>
<div id="resBox2">
<a href="move_reservation.php?id=<?=$gameID?>&resBox=2" ><img src = images/reservation/res_2_grey.png id = "resButton2"></a>
</div>
<div id="resBox3">
<a href="move_reservation.php?id=<?=$gameID?>&resBox=3" ><img src = images/reservation/res_3_grey.png id = "resButton3"></a>
</div>
</div>

</div>
</body>
 
 <script>
 var currentGameID = <?php echo $gameID;?>;
   var gameHistory = <?php echo json_encode($temparray);?>;
   var phpColor = "<?php echo $_COOKIE['current_user_cookie']; ?>";

   function showMenu(){
    document.getElementById("popupMenuId").classList.toggle("menuShow");
    document.getElementById("menuBox").classList.toggle("turnRed");
   }
    </script>

<script src= "scripts/slo_shogi_script.js"></script>