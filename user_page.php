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

<form action="gameboard.php" name = "testpostlink" method = "post">
<input type="hidden" name = "pdata" value = "2">
</form>
<a href=# onclick="submitTestPostLink()">TEST GAME</a> 
<!-- the above will need to be re-written with Javascript -->
<br>
    </body>
