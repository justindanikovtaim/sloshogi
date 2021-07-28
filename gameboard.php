<?php 
session_start();
$gameID = $_GET['id'];
$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
$result = mysqli_query($link, 'SELECT * FROM gamerecord WHERE id = '.$gameID); //get all the current from moves

?>

<!DOCTYPE HTML>
<html onload ="drawBoard()">
<head>
    <meta charset="utf-8">
    <title>Slo Shogi</title>
    
    <link href="CSS/Gameboard_style_sheet.css" rel="stylesheet">
    <link href="CSS/all_pages.css" rel="stylesheet">

</head>

<body bgcolor="#f0e68c">

    <div id = wholeBoard>
    <div id = "whiteMochigoma"></div>
    <div id = "board"></div>
    <div id = "blackMochigoma"></div>
    </div>
 <h3 id = "playerPrompt"></h3> 

 <div id = "skipButtons">
 <button class = "skipButton" id = "skipBack" onClick = "skipBack()">≪</button>
 <button class = "skipButton" id = "back" onClick = "stepBack()"> < </button>
 <button class = "skipButton" id = "forward" onClick = "stepForward()"> > </button>
 <button class = "skipButton" id = "skipForward" onClick = "skipForward()">≫</button>
 
</div>


<a href="user_page.php"> <img src = "images/return.png"  id = "toUserPage"> </a>
<a href="move_reservation.php?id=<?=$gameID?>" id = "toReservation">予約</a>
<img src = "images/resign.png" id = "resignButton" onClick = "resign()">
</body>

<?php
$temparray = array();
$row = mysqli_fetch_array($result);
array_push($temparray,$row["moves"], $row["blackplayer"], $row["whiteplayer"], $row["reservation"], $row["status"]); 
 ?>
 
 <script>
 var currentGameID = <?php echo $gameID;?>;
   var gameHistory = <?php echo json_encode($temparray) ; ?>;
   var phpColor = "<?php echo $_COOKIE['current_user_cookie']; ?>";
    console.log(gameHistory);

    </script>

<script src= "scripts/slo_shogi_script.js"></script>