<?php

$in = file_get_contents('php://input');
$decoded = json_decode($in, true);
$gametofind = $decoded['gameId'];
$turn = $decoded['turn'];

require 'connect.php';

    $updatecommand = 'UPDATE gamerecord SET moves = CONCAT(moves, "' . $decoded['newmoves'] . '"), turn = "'.$turn.'" WHERE id ='; 

echo $updatecommand;

    mysqli_query($link, $updatecommand.$gametofind);
    mysqli_query($link, 'UPDATE gamerecord SET reservation = "" WHERE id = '.$gametofind);//empty the reservation

 ?>