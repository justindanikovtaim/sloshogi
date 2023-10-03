<?php

require_once SHAREDPATH . 'database.php';

session_start();
$problemToFind = $_GET['id'];

$result = safe_sql_query("SELECT boardSetup, mochigomaSetup, problemName FROM tsumeshogi WHERE id = ?", ['i', $problemToFind]); //get all the current from moves
$row = mysqli_fetch_array($result);

//set the session variables before navigating to the edit page
$_SESSION['boardConfig'] = $row['boardSetup'];
$_SESSION['mgConfig'] = $row['mochigomaSetup'];
$_SESSION['problemName'] = $row['problemName'];
$_SESSION['problemId'] = $problemToFind;
header('location: /slotsumeshogi/initialize-tsume');
