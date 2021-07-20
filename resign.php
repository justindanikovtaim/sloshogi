<?php

$json = file_get_contents('php://input');
$decoded = json_decode($json, true);
$gametofind = $decoded['id'];

$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/

    $resignCommand = 'UPDATE gamerecord SET status = 3 WHERE id ='; 

echo $resignCommand;
echo "the winner is ".$decoded['winner'];
echo " and the loser is ".$decoded['loser'];
    mysqli_query($link, $resignCommand.$gametofind);

 ?>