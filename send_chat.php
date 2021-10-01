<?php

require "connect.php";

$in = file_get_contents('php://input');
$decoded = json_decode($in, true);
$gametofind = $decoded['gameId'];
$chatSeen = $decoded['chatSeenNum'];

$textToAdd = "%%".$_COOKIE['current_user_cookie']."%%".$decoded['textToSend'];

    $updatecommand = 'UPDATE gamerecord SET chat = CONCAT(chat, "' . $textToAdd. '"), chatseen = '.$chatSeen.' WHERE id ='; 

    mysqli_query($link, $updatecommand.$gametofind);
    echo msqli_error($link);

 ?>