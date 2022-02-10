<?php


$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
if(mysqli_connect_errno()){
    header('location: db_error.html');
    die();
}

?>