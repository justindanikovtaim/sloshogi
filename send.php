<?php
require 'connect.php';
$in = file_get_contents('php://input');
$decoded = json_decode($in, true);
$gametofind = $decoded['gameId'];
$playerColor = $decoded['color'];
if($playerColor == 'B'){
        $opColor = 'W';
        $playerTime = "blackTimeOn";
        $opTime = "whiteTimeOn";
}else{
    $opColor = 'B';
    $playerTime = "whiteTimeOn";
    $opTime = "blackTimeOn";
}

$turn = $decoded['turn'];
$deleteRule = $decoded['delete'];
$chatSeen = $decoded['chatSeen'];

//make sure data wasn't send multiple times
$checkForDuplicateSend = mysqli_query($link, 'SELECT turn FROM gamerecord WHERE id = "'.$gametofind.'"');
    $lastTurn = mysqli_fetch_array($checkForDuplicateSend);
    if($lastTurn['turn'] != $turn){ //if not sending a duplicate, update everything

$delete1 = 'reservation1 = "",';
$chop1 = 'reservation1 = SUBSTR(reservation1,(1+CHAR_LENGTH(SUBSTRING_INDEX(reservation1,";", 3)))),';
$delete2 = 'reservation2 = "",';
$chop2 = 'reservation2 = SUBSTR(reservation2,(1+CHAR_LENGTH(SUBSTRING_INDEX(reservation2,";", 3)))),';
$delete3 = 'reservation3 = "",';
$chop3 = 'reservation3 = SUBSTR(reservation3,(1+CHAR_LENGTH(SUBSTRING_INDEX(reservation3,";", 3)))),';

//write code for delete command
    switch($deleteRule){
        case "1":
            $deleteCommand = $chop1.$delete2.$delete3;
            break;
        case "2":
            $deleteCommand = $delete1.$chop2.$delete3;
            break;
        case "3":
            $deleteCommand = $delete1.$delete2.$chop3;
            break;
        case "12":
            $deleteCommand = $chop1.$chop2.$delete3;
            break;
        case "13":
            $deleteCommand = $chop1.$delete2.$chop3;
            break;
        case "123":
            $deleteCommand = $chop1.$chop2.$chop3;
            break;
        case "23":
            $deleteCommand = $delete1.$chop2.$chop3;
            break;
        case "skip":
            $deleteCommand = $delete1.$delete2.$delete3;
            break;
        default:
        echo "There was an error with the switch command";
        break;
    }
    $updatecommand = 'UPDATE gamerecord SET moves = CONCAT(moves, "' . $decoded['newmoves'] . '"),'.$deleteCommand.' turn = "'.$turn.'", chatseen = '.$chatSeen.',
    lastMoveTime = '.$playerTime.'- moveTimestamp'.$playerColor.',
    moveTimestamp'.$opColor.' = '.$opTime.', moveTimestamp'.$playerColor.' = '.$playerTime.'  WHERE id ='; 


    mysqli_query($link, $updatecommand.$gametofind);
}else{
    echo "<script>console.log('already played')</script>";
}
 ?>