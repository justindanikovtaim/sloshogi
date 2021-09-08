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
<a id = "backButton" href = "user_page.php">≪</a>
<h1>Friends</h1>
<div id = "drawFriends"></div>
<br>
<a href = "#">Add New Friends</a>
<br>
<a href = "#">Send Email Invite</a>

</body>
<script>

let friendsArrayLength = Number(<?php echo $numOfFriends; ?>);
let friendLinks = [];
let counter = 0;

//make a php foreach loop to create each name in the friendLinks array as a a element in the DOM
<?php foreach($findFriends as $i){?>
        
   friendLinks[counter] = document.createElement("a");
   friendLinks[counter].innerHTML = "<?=$i?>";
   friendLinks[counter].setAttribute("href", "view_friend.php?friendName=<?=$i?>");
   document.getElementById("drawFriends").appendChild(friendLinks[counter]);
   document.getElementById("drawFriends").innerHTML += "<br>";
   counter++
  <?php  }   ?>
</script>