<?php

require_once SHAREDPATH . "database.php";
require_once SHAREDPATH . "session.php";

function updateChat($decoded)
{
    $gametofind = $decoded['gameId'];
    $chatSeen = $decoded['chatSeenNum'];
    $textToAdd = "%%" . getCurrentUser() . "%%" . $decoded['textToSend'];
    $updateCommand = 'UPDATE gamerecord SET chat = CONCAT(chat, "' . $textToAdd . '"), chatseen = ' . $chatSeen . ' WHERE id = ?';

    safe_sql_query($updateCommand, ['s', $gametofind]);
}

$in = file_get_contents('php://input');
$decoded = json_decode($in, true);

updateChat($decoded);
