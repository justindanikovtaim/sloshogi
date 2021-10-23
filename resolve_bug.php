<?php
require 'connect.php';
if(mysqli_query($link, "DELETE FROM feedback WHERE id ='".$_GET['id']."'")){
    echo"<h1>Resolved successfully</h1>";
    header('location: dashboard.php');
};
?>