<?php 
require 'connect.php';

$tsumeID = $_GET['id'];
$result = mysqli_query($link, 'SELECT * FROM tsumeshogi WHERE id = "'.$tsumeID.'"'); //get all the current from moves
if($result->num_rows == 0 ){
    //if a problem id that doesn't exist is input go back to tsumeshogi main page
    header('location: slo_tsumeshogi.php');
    die();
}

$row = mysqli_fetch_array($result);
$setup = $row['boardSetup'];
$mgSetup = $row['mochigomaSetup'];
$timeLimit = $row['timeLimit'];
$creator = $row['createdBy'];
$sequence =$row['mainSequence'];
$problemName = $row['problemName'];

?>

<!DOCTYPE HTML>
<html onload ="drawBoard()">
<head>
    <meta charset="utf-8">
    <meta name="format-detection" content ="telephone=no">
    <title>Slo Tsumeshogi</title>
    <link href="CSS/all_pages.css" rel="stylesheet">
    <link href="CSS/tsume_style_sheet.css" rel="stylesheet">

</head>

<body>
<div id = "wholeBoard">
<div id ="boardColor">
<a href = "slo_tsumeshogi.php"id = "backButton">≪</a>


    <div id = "whiteMochigoma"></div>

    <div id = "board"></div>
    <div id = "blackMochigoma"></div>
 
</div>


<div id="menuBox" onclick = "showMenu()">
<img src="images/menu_button.png"  id = "menuButton">
<img class ="msgIcon" src="images/new_message_icon.png" id ="newMessage">
</div>

    <div class = "popupMenu" id="popupMenuId">
    <a href="feedback_form.php?src=gameboard&id=<?=$gameID?>">バグ報告・Report a bug</a>
</div>

    <div id = "promptBox">
 <h3 id = "playerPrompt"></h3> 
</div>

 <div id = "skipButtons">
 <button class = "skipButton" id = "fullBack" onClick = "resetTsume()">リセット</button>

 <a href = "tsume.php?id=<?=$tsumeID + 1?>"><button class = "skipButton" id = "fullForward">≫</button></a>
 
</div>

</body>
 
 <script>
 var currentGameID = <?=$tsumeID?>;
   let setup = "<?=$setup?>";
   var gameState = setup.split(",");
   let mgArray = "<?=$mgSetup?>";
   var mochiGomaArray = mgArray.split(",");
   var phpColor = "<?=$_COOKIE['current_user_cookie']?>";
   let sequence = "<?=$sequence?>";
   var mainSequence = sequence.split(",");
   var problemName = "<?=$problemName?>";

   function showMenu(){
    document.getElementById("popupMenuId").classList.toggle("menuShow");
    document.getElementById("menuBox").classList.toggle("turnRed");
   }


    </script>
    <script src= "scripts/tsume_shared.js"></script>
<script src= "scripts/tsume_script.js"></script>

