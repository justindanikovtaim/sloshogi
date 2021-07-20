<?php

$json = file_get_contents('php://input');
$decoded = json_decode($json, true);
$gametofind = $decoded['id'];

$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/

    $getUserRecord = mysqli_query($link, "SELECT record FROM users WHERE username = '".$decoded['winner']."'");
    $userRecordArray = mysqli_fetch_row($getUserRecord);
    $winnerRecordExploded = explode(",", $userRecordArray[0]);
    $winnerRecord = 'W: '.((int)substr($winnerRecordExploded[0], 1) + 1).','.$winnerRecordExploded[1];
    //add one to the wins
    echo $winnerRecord;
    $getUserRecord = mysqli_query($link, 'SELECT record FROM users WHERE username = "'.$decoded['loser'].'"');
    $userRecordArray = mysqli_fetch_row($getUserRecord);
    $loserRecordExploded = explode(",", $userRecordArray[0]);
    $loserRecord = $loserRecordExploded[0].",". " L: ".((int)substr($loserRecordExploded[1], 1) + 1); 
    //add one to the losses
echo $loserRecord;
    $resignCommand = 'UPDATE gamerecord SET status = 3 WHERE id ='; 
    $plusWinCommand = "UPDATE users SET record = '" .$winnerRecord. "' WHERE username = '" .$decoded['winner']."'";
    $plusLossCommand =  "UPDATE users SET record = '" .$loserRecord. "' WHERE username = '" .$decoded['loser']."'";

echo $resignCommand;

    mysqli_query($link, $resignCommand.$gametofind);
    mysqli_query($link, $plusWinCommand);
    mysqli_query($link, $plusLossCommand);
 ?>