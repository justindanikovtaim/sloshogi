<?php
require 'connect.php';
$result = mysqli_query($link, "SELECT id, problemName, createdBy, completed FROM tsumeshogi WHERE published = 1");


?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>Slo Tsumeshogi List</title>
    <link href="CSS/all_pages.css" rel="stylesheet">
</head>
<body>
<a href = "slo_tsumeshogi.php"id = "backButton">≪</a><br>
    <h1>SLO詰将棋問題集</h1>
    <?php 
    while($row = mysqli_fetch_array($result)){
        //list all of the tsume shogi problems
        $tempVar = explode(";", $row['completed']);
        //make the url color different
        if(in_array($_COOKIE['current_user_cookie'], $tempVar)){
            echo "<a class = 'problemList' style='color: grey' href = 'tsume.php?id=".$row['id']."'>#".$row['id']." ".$row['problemName']." by ".$row['createdBy']."</a><br>";
        }else{
            echo "<a class = 'problemList' style='color: black' href = 'tsume.php?id=".$row['id']."'>#".$row['id']." ".$row['problemName']." by ".$row['createdBy']."</a><br>";
        }
    }