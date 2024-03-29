<?php 
require 'connect.php';

session_start();
$gameID = $_GET['id'];
// 6/27 reducing the reservation buttons from 3 to 1
//$reservationSlot = "reservation".$_GET['resBox'];

$result = mysqli_query($link, 'SELECT * FROM gamerecord WHERE id = '.$gameID); //get all the current from moves
if(isset($_GET['komaSet'])){
    $komaSet = $_GET['komaSet'];  
}else{
    $komaSet = 1;
}
?>

<!DOCTYPE HTML>
<html onload ="drawBoard()">
<head>
    <meta charset="utf-8">
    <title>Slo Shogi - Reserve</title>
    
    <link href="CSS/reservation.css" rel="stylesheet">
    <link href="CSS/all_pages.css" rel="stylesheet">

</head>

<body bgcolor="#f0e68c">

    <div id = wholeBoard>
    <div id = "whiteMochigoma"></div>
    <div id = "board"></div>
    <div id = "blackMochigoma"></div>
    </div>
 <h3 id = "playerPrompt"></h3>   
<a href="gameboard.php?id=<?=$_GET['id']?>"> <img src = "images/return.png"  id = "toUserPage"> </a>
<img src = "images/submit.png" id = "submitButton" onClick = "sendMoveData()">
</body>

<?php
$temparray = array();
$row = mysqli_fetch_array($result);
array_push($temparray,$row["moves"], $row["blackplayer"], $row["whiteplayer"]); 
 ?>
 
 <script>
 var currentGameID = <?php echo $gameID;?>;
 var reservationSlot = "<?= $reservationSlot?>";
   var gameHistory = <?php echo json_encode($temparray) ; ?>;
   var phpColor = "<?php echo $_COOKIE['current_user_cookie']; ?>";
   var komaSet = <?=$komaSet?>;
    </script>

<script src= "scripts/move-reservation.js"></script>