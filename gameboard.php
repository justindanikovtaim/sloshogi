<?php 
session_start();
$gameID = $_POST['pdata'];
echo $gameID;
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

<body>

    <div id = wholeBoard>
    <div id = "whiteMochigoma"></div>
    <div id = "board"></div>
    <div id = "blackMochigoma"></div>
    </div>
    <input type = "submit", id = "submitmovebutton", value = "submit move", onclick = "sendMoveData(), disableSubmit()">
    <input type = "submit", value = "reset game", id = "resetbutton", onclick = "resetGame()">
 <h3 id = "playerPrompt"></h3>   
<a href="user_page.php" id = "toUserPage">Back to User Page</a> 

</body>

<?php
$temparray = array();
$row = mysqli_fetch_array($result);
array_push($temparray,$row["moves"], $row["blackplayer"], $row["whiteplayer"]); 
 ?>
 
 <script>
   var gameHistory = <?php echo json_encode($temparray) ; ?>;
   var phpColor = "<?php echo $_COOKIE['current_user_cookie']; ?>";
    console.log(gameHistory);
    </script>

<script src= "scripts/slo_shogi_script.js"></script>