
<?php 
$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
  

?>
<!DOCTYPE html>
<head>
<link href="CSS/all_pages.css" rel="stylesheet">
</head>
<body>
<a id = "backButton" href = "newGame.html.php">≪</a>
<h1>オープン対戦を作成</h1>

<form action="new_open_game.php" name = "openGameData" method = "post">
<h3>ユーザーの色</h3>
<input type = "radio" id = "userColorBlack" name = "userColor" value = "blackplayer">
<label for="userColorBlack">黒</label><br>
<input type = "radio" id = "userColorWhite" name = "userColor" value = "whiteplayer">
<label for="userColorWhite">白</label><br>
<input type="submit" value="対戦作成">
</form>

</body>



