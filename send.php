<?php

$in = file_get_contents('php://input');
$decoded = json_decode($in, true);
$gametofind = "2";

$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/

    $updatecommand = 'UPDATE gamerecord SET moves = CONCAT(moves, "' . $decoded['newmoves'] . '") WHERE id ='; 

echo $updatecommand;

    mysqli_query($link, $updatecommand.$gametofind);

 ?>