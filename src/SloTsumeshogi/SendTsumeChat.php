<?php

require SHAREDPATH . "database.php";
require SHAREDPATH . "session.php";

$in = file_get_contents('php://input');
$decoded = json_decode($in, true);
$gametofind = $decoded['gameId'];

$textToAdd = "%%" . getCurrentUser() . "%%" . $decoded['textToSend'];

$updatecommand = 'UPDATE tsumeshogi SET chat = CONCAT(chat, "' . $textToAdd . '") WHERE id = ?';

safe_sql_query($updatecommand, ['i', $gametofind]);
