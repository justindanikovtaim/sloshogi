<?php

require_once SHAREDPATH . 'database.php';

$in = file_get_contents('php://input');
$decoded = json_decode($in, true);
$gametofind = $decoded['gameId'];
$reservationSlot = $decoded['reservationSlot'];

$updatecommand = 'UPDATE gamerecord SET ? = ? WHERE id = ?';

// echo $updatecommand;

safe_sql_query($updatecommand, ['isi', $reservationSlot, $gametofind, $gametofind]);
