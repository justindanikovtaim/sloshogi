<?php 
require 'connect.php';
?>

<!DOCTYPE html>
<head>
<link href="CSS/all_pages.css" rel="stylesheet">
</head>

<body>
<a id = "backButton" href = "friends.php">≪</a>
<br>
<br>
<h3>友達へ招待リンクを送ります Send and invite link to a friend</h3>
<form action="send_invite.php" method ="post">
    <label for="email">友達のアドレス Friend's Email</label><br>
    <input name="email" type = "email"><br>
    <label for="msg">友達へのメッセージ Message for your friend</label>
    <input name="msg" type="text">
    <input type="submit" value="送信　Send">
</form>
</body>

