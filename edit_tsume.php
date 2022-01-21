<?php 
require 'connect.php';
session_start();
$problemToFind = $_GET['id'];

$result = mysqli_query($link, "SELECT boardSetup, mochigomaSetup, problemName FROM tsumeshogi WHERE id ='".$problemToFind."'");
$row = mysqli_fetch_array($result);

//set the session variables before navigating to the edit page
$_SESSION['boardConfig'] = $row['boardSetup'];
$_SESSION['mgConfig'] = $row['mochigomaSetup'];
$_SESSION['problemName'] = $row['problemName'];
$_SESSION['problemId'] = $problemToFind;
header('location: initialize_tsume.php');

?>