<?php

require_once SHAREDPATH . "database.php";
require_once SHAREDPATH . 'session.php';

$in = file_get_contents('php://input');
$decoded = json_decode($in, true);
$gametofind = $decoded['gameId'];
$chatSeen = $decoded['chatSeenNum'];

$textToAdd = "%%" . getCurrentUser() . "%%" . $decoded['textToSend'];

$updatecommand = 'UPDATE gamerecord SET chat = CONCAT(chat, ?), chatseen = ? WHERE id = ?';

safe_sql_query($updatecommand, ['ssi', $textToAdd, $chatSeen, $gametofind]);
