<?php

$json = file_get_contents('php://input');
$decoded = json_decode($json, true);
$gametofind = $decoded['id'];

require 'connect.php';

$gameOverCommand = 'UPDATE gamerecord SET status = 3 WHERE id ='; 
mysqli_query($link, $gameOverCommand.$gametofind);

?>