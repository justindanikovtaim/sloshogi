<?php


$json = file_get_contents('php://input');
$decoded = json_decode($json, true);
$gametofind = $decoded['id'];

require 'connect.php';

    $getUserRecord = mysqli_query($link, "SELECT record FROM users WHERE username = '".$decoded['winner']."'");
    $userRecordArray = mysqli_fetch_row($getUserRecord);
    $winnerRecordExploded = explode(",", $userRecordArray[0]);
    $addToRecord = 1 + (int)substr($winnerRecordExploded[0], 3);
    $winnerRecord = 'W: '.(string)$addToRecord.','.$winnerRecordExploded[1];
    //add one to the wins
   // echo $winnerRecord;
    $getUserRecord = mysqli_query($link, 'SELECT record FROM users WHERE username = "'.$decoded['loser'].'"');
    $userRecordArray = mysqli_fetch_row($getUserRecord);
    $loserRecordExploded = explode(",", $userRecordArray[0]);
   
    $addToRecord = 1 + (int)substr($loserRecordExploded[1], 3);
    $loserRecord = $loserRecordExploded[0].",". " L: ".(string)$addToRecord; 
    //add one to the losses
//echo $loserRecord;
    $resignCommand = 'UPDATE gamerecord SET status = 5, 
    moves = CONCAT(moves, ",100,100,100") WHERE id ='; //100 = resign
    $addWinnerCommand = "UPDATE gamerecord SET winner = '".$decoded['winner']."' WHERE id = '".$gametofind."'";
    $plusWinCommand = "UPDATE users SET record = '" .$winnerRecord. "' WHERE username = '" .$decoded['winner']."'";
    $plusLossCommand =  "UPDATE users SET record = '" .$loserRecord. "' WHERE username = '" .$decoded['loser']."'";

echo $resignCommand;
echo $plusWinCommand;
echo $plusLossCommand;

    mysqli_query($link, $resignCommand.$gametofind);
    mysqli_query($link, $addWinnerCommand);
    mysqli_query($link, $plusWinCommand);
    mysqli_query($link, $plusLossCommand);
 ?>