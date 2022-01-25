
<?php 
require 'connect.php';
$userVar = "'".$_COOKIE['current_user_cookie']."'";
$getFriends =  mysqli_query($link, 'SELECT friends FROM users WHERE username = '.$userVar); 
$friendIds = explode(',', mysqli_fetch_array($getFriends)['friends']);//should separate the friend list by commas
$numOfFriends = count($friendIds);
//$findFriends = [];
    for($x = 0; $x < $numOfFriends; $x ++){
        $getData = mysqli_query($link, "SELECT username FROM users WHERE id = '".$friendIds[$x]."'");
    $findFriends[$x] = mysqli_fetch_row($getData)[0];
    }
    

?>
<!DOCTYPE html>
<head>
<link href="CSS/all_pages.css" rel="stylesheet">
</head>
<body>
<a id = "backButton" href = "newGame.php">≪</a>
<br>
<h1>友達をチャレンジ</h1>
<div class = "inputBox">
<datalist id = "drawFriends"></datalist>
<form action="new_challenge.php" name = "challengeData" method = "post">
<label for = "opponentField">対戦相手</label><br>
<input list="drawFriends" name = "opponent" id = "opponentField" require>
</div>

<br>
<div class = "inputBox">
<h3>先手</h3>
<input type = "radio" id = "userColorBlack" name = "userColor" value = "blackplayer">
<label for="userColorBlack">私</label><br>
<input type = "radio" id = "userColorWhite" name = "userColor" value = "whiteplayer">
<label for="userColorWhite">相手</label><br>
</div>

<br>
<div class = "inputBox">
<h3>公開設定</h3>
<input type ="radio" id = "private" name = "publicPrivate" value = 1>
<label for="private">非公開</label><br>
<input type ="radio" id = "notPrivate" name = "publicPrivate" value = 0>
<label for="notPrivate">公開</label><br>
</div>

<input type="submit" value="チャレンジ送信">

</form>

</body>
<script>
/*function fillInName(opponent){
    document.getElementById("opponentField").value = opponent;
    document.getElementById("showOpponent").innerHTML = opponent;
}*/

let friendsArrayLength = Number(<?php echo $numOfFriends; ?>);
let friendLinks = [];
let counter = 0;

//make a php foreach loop to create each name in the friendLinks array as a p element in the DOM
<?php foreach($findFriends as $i){?>
   //https://developer.mozilla.org/en-US/docs/Web/HTML/Element/datalist     
   friendLinks[counter] = "<option value = '<?=$i?>'>";
   //friendLinks[counter].setAttribute("onClick", "fillInName('<?=$i?>')");
   //friendLinks[counter].setAttribute("href", "#");
   document.getElementById("drawFriends").innerHTML += friendLinks[counter];
   //document.getElementById("drawFriends").innerHTML += "<br>";
   counter++
  <?php  }   ?>
</script>


