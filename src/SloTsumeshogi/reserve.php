<?php
require 'connect.php';

$in = file_get_contents('php://input');
$decoded = json_decode($in, true);
$gametofind = $decoded['gameId'];
$reservationSlot = $decoded['reservationSlot'];

    $updatecommand = 'UPDATE gamerecord SET '.$reservationSlot.' = "' . $decoded['reservationmoves'] . '" WHERE id ='; 

echo $updatecommand;

    mysqli_query($link, $updatecommand.$gametofind);

 ?>