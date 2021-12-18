<?php
session_start();
$_SESSION['boardConfig'] = $_POST['boardConfig'];
$_SESSION['mgConfig'] = $_POST['mochigomaConfig'];
?>

<!DOCTYPE html>
<head>
    <meta charset = "utf-8">
    <title>Setup Slo tsumeshogi</title>
    <link href="CSS/all_pages.css"  rel="stylesheet">
    <link href="CSS/tsume_style_sheet.css" rel="stylesheet">
    <link href="CSS/create_tsume.css" rel="stylesheet">
</head>
<body>
<div id = "wholeBoard">
<div id ="boardColor">
<a href = "initialize_tsume.php"id = "backButton">≪</a>


    <div id = "whiteMochigoma"></div>

    <div id = "board"></div>
    <div id="choosePieceId" class = "choosePiece"></div>
    <div id = "blackMochigoma"></div>
 
</div>


<div id="menuBox" onclick = "showMenu()">
<img src="images/menu_button.png"  id = "menuButton">
<img class ="msgIcon" src="images/new_message_icon.png" id ="newMessage">
</div>
    <div class = "popupMenu" id="popupMenuId">
    <a href="feedback_form.php?src=set_tsume">バグ報告・Report a bug</a>
</div>

    <div id = "promptBox">
 <h3 id = "playerPrompt"></h3> 
</div>

 <div id = "skipButtons">
 <button id = "next" onClick = "toSavePage()">保存</button>
 
</div>

<form id = "tsumeData" method="post" action="save_tsume.php">
    <label for="problemName">問題の名前を入力してください</label>
    <input type="text" name = "problemName" id ="problemName">
    <br>
    <label for="timelimit">タイマー（秒単位)</label>
    <input type="number" name = "timeLimit" id="timeLimit">）
    <input type="submit" value="問題を保存">
    <input type="hidden" name = "boardConfig" id="boardConfig" value = "<?=$_SESSION['boardConfig']?>">
    <input type="hidden" name = "mochigomaConfig" id="mochigomaConfig" value = "<?=$_SESSION['mgConfig']?>">
    <input type = "hidden" name = "moveSequence" id = "moveSequence">
</form>

</body>
<script>
    let tempstring = "<?=$_POST['boardConfig']?>";
    var gameState = tempstring.split(",");
    tempstring = "<?=$_POST['mochigomaConfig']?>";
    var mochiGomaArray = tempstring.split(","); 

    function showMenu(){
    document.getElementById("popupMenuId").classList.toggle("menuShow");
    document.getElementById("menuBox").classList.toggle("turnRed");

   }

</script>

<script src= "scripts/tsume_shared.js"></script>
<script src= "scripts/tsume_set.js"></script>
