<?php 
require 'connect.php';
/*
$tsumeID = $_GET['id'];
$result = mysqli_query($link, 'SELECT * FROM tsumeshogi WHERE id = '.$gameID); //get all the current from moves
if($result->num_rows == 0 ){
    //if a game id that doesn't exist is input
    header('location: user_page.php');
    die();
}

$row = mysqli_fetch_array($result);
$setup = $row['boardSetup'];
$timeLimit = $row['timeLimit'];
$creator = $row['createdBy'];
*/

?>

<!DOCTYPE HTML>
<html onload ="drawBoard()">
<head>
    <meta charset="utf-8">
    <meta name="format-detection" content ="telephone=no">
    <title>Slo Tsumeshogi</title>
    <link href="CSS/all_pages.css" rel="stylesheet">
    <link href="CSS/Gameboard_style_sheet.css" rel="stylesheet">

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
    <a href="feedback_form.php?src=gameboard&id=<?=$gameID?>">バッグ報告・Report a bug</a>
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

</body>
 
 <script>
 
   function showMenu(){
    document.getElementById("popupMenuId").classList.toggle("menuShow");
    document.getElementById("menuBox").classList.toggle("turnRed");
   }


    </script>
<script src= "scripts/create_tsume_script.js"></script>
