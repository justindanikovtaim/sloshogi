<?php


$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/

 $updatecommand = 'UPDATE gamerecord SET moves = "" WHERE id ='; 
 $gametofind = "2";
echo $updatecommand;
 mysqli_query($link, $updatecommand.$gametofind); 
 ?>