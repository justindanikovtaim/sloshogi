<?php

$json = file_get_contents('php://input');
$decoded = json_decode($json, true);
$gametofind = $decoded['id'];

require_once SHAREDPATH . 'database.php';

$getUserRecord = safe_sql_query("SELECT record FROM users WHERE username = ?", ['s', $decoded['winner']]);
$userRecordArray = mysqli_fetch_row($getUserRecord);
$winnerRecordExploded = explode(",", $userRecordArray[0]);
$addToRecord = 1 + (int)substr($winnerRecordExploded[0], 3);
$winnerRecord = 'W: ' . (string)$addToRecord . ',' . $winnerRecordExploded[1];
//add one to the wins
// echo $winnerRecord;
$getUserRecord = safe_sql_query("SELECT record FROM users WHERE username = ?", ['s', $decoded['loser']]);
$userRecordArray = mysqli_fetch_row($getUserRecord);
$loserRecordExploded = explode(",", $userRecordArray[0]);

$addToRecord = 1 + (int)substr($loserRecordExploded[1], 3);
$loserRecord = $loserRecordExploded[0] . "," . " L: " . (string)$addToRecord;
//add one to the losses
//echo $loserRecord;
$resignCommand = 'UPDATE gamerecord SET status = 5,
    moves = CONCAT(moves, ",100,100,100") WHERE id = ?'; //100 = resign
$addWinnerCommand = "UPDATE gamerecord SET winner = ? WHERE id = ?";
$plusWinCommand = "UPDATE users SET record = ? WHERE username = ?";
$plusLossCommand =  "UPDATE users SET record = ? WHERE username = ?";

safe_sql_query($resignCommand, ['i', $gametofind]);
safe_sql_query($addWinnerCommand, ['si', $decoded['winner'], $gametofind]);
safe_sql_query($plusWinCommand, ['ss', $winnerRecord, $decoded['winner']]);
safe_sql_query($plusLossCommand, ['ss', $loserRecord, $decoded['loser']]);
