<?php
//$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
require 'connect.php';
$in = file_get_contents('php://input');
$decoded = json_decode($in, true);
$id = $decoded['id'];
$seconds = $decoded['seconds']*1;//* 1makes sure that it's a number
$color = $decoded['color'];

mysqli_query($link, "UPDATE gamerecord SET ".$color."TimeOn = ".$color."TimeOn + ".$seconds." WHERE id = '".$id."'");
?>