
<?php 
$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
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
<h1>Choose a Friend to Challenge</h1>
<div id = "drawFriends"></div>
<form action="new_challenge.php" name = "challengeData" method = "post">
<label for="opponent">Enter Username of Opponent to Challenge</label>
<br>
<input type="text" name = "opponent" id = "opponentField" >
<input type="submit" value="Send Challenge">
</form>

</body>
<script>
function fillInName(opponent){
    document.getElementById("opponentField").value = opponent;
    console.log(opponent);
}

let friendsArrayLength = Number(<?php echo $numOfFriends; ?>);
let friendLinks = [];
let counter = 0;

//make a php foreach loop to create each name in the friendLinks array as a p element in the DOM
<?php foreach($findFriends as $i){?>
        
        friendLinks[counter] = document.createElement("a");
   friendLinks[counter].innerHTML = "<?=$i?>";
   friendLinks[counter].setAttribute("onClick", "fillInName('<?=$i?>')");
   friendLinks[counter].setAttribute("href", "#");
   document.getElementById("drawFriends").appendChild(friendLinks[counter]);
   document.getElementById("drawFriends").innerHTML += "<br>";
   counter++
  <?php  }   ?>
</script>


