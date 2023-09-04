<?php
require 'connect.php';
$result = mysqli_query($link, "SELECT id, problemName, createdBy, published FROM tsumeshogi WHERE createdBy = '".$_COOKIE['current_user_cookie']."'");


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
        echo "<a class = 'problemList' href = 'tsume.php?id=".$row['id']."'>#".$row['id']." ".$row['problemName']." by ".$row['createdBy']."</a>";
        echo "<a class = 'problemList' href = 'edit_tsume.php?id=".$row['id']."'><button>編集</button></a>"; //this is the edit button
        //see if the problem is published or not and make a 'publish' or 'unpublish' button
        if($row['published'] == '0'){
            echo "<a class = 'problemList' href = 'publish_tsume.php?id=".$row['id']."&pUp=1'><button>公開する</button></a><br>";
        }else{
            echo "<a class = 'problemList' href = 'publish_tsume.php?id=".$row['id']."&pUp=0'><button>非公開にする</button></a><br>";
        }
    }