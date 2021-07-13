<?php
session_start();

$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
$getGameId = mysqli_query($link, "SELECT id FROM gamerecord WHERE  blackplayer = '".$_COOKIE['current_user_cookie'] ."' OR whiteplayer = '".$_COOKIE['current_user_cookie'] ."'" );
$gameidkenchris = mysqli_fetch_array($getGameId, MYSQLI_NUM);
?>
<!DOCTYPE HTML>
<head>
    <script>
        function submitPostLink(){
            document.postlink.submit();
        }
        function submitTestPostLink(){
            document.testpostlink.submit();

        }
    </script>
    <link href="CSS/all_pages.css" rel="stylesheet">
 </head>
 <body>
<h1><?php echo $_COOKIE['current_user_cookie']."'s";?> Page</h1>
<br>
<h3>Current Games</h3>
<br>
<form action="gameboard.php" name = "postlink" method = "post">
<input type="hidden" name = "pdata" value = "<?php echo $gameidkenchris[0]; ?>">
</form>
<a href=# onclick="submitPostLink()">Ken vs. Cru</a> 
<br>
</form>
<a href=# onclick="submitTestPostLink()">TEST GAME</a> 
<br>
     <h3>Friends</h3>
<br>
<form action="gameboard.php" name = "postlink" method = "post">
<input type="hidden" name = "pdata" value = "<?php echo $gameidkenchris[0]; ?>">
</form>
<a href=# onclick="submitPostLink()">Friends List</a> 
<br>
</form>
<a href=# onclick="submitPostLink()">Friend Request</a> 
<br>
</form>
<a href=# onclick="submitPostLink()">Add new Friends</a> 
<br>
<form action="gameboard.php" name = "postlink" method = "post">
<input type="hidden" name = "pdata" value = "<?php echo $gameidkenchris[0]; ?>">

<form action="gameboard.php" name = "testpostlink" method = "post">
<input type="hidden" name = "pdata" value = "2">
<!-- the above will need to be re-written with Javascript -->
<br>
    </body>
