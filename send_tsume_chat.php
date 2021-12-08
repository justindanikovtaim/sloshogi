<?php

require "connect.php";

$in = file_get_contents('php://input');
$decoded = json_decode($in, true);
$gametofind = $decoded['gameId'];

$textToAdd = "%%".$_COOKIE['current_user_cookie']."%%".$decoded['textToSend'];

    $updatecommand = 'UPDATE tsumeshogi SET chat = CONCAT(chat, "' . $textToAdd. '") WHERE id ='; 

    mysqli_query($link, $updatecommand.$gametofind);
    echo mysqli_error($link);

 ?>