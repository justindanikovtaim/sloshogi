<?php
require 'connect.php';

$json = file_get_contents('php://input');//https://www.geeksforgeeks.org/how-to-receive-json-post-with-php/
$gametofind = json_decode($json, true); //adding the 'true' here is critical, but not shown in the page above
echo print_r($gametofind);

 $updatecommand = 'UPDATE gamerecord SET moves = "" WHERE id ='; 
echo $updatecommand;
 mysqli_query($link, $updatecommand.$gametofind['id']); 
 ?>