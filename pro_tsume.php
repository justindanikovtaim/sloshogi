<?php
require 'connect.php';
$result = mysqli_query($link, "SELECT id, problemName, createdBy, published FROM tsumeshogi WHERE createdBy = 'kayanokoithi'");


?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>Slo Tsumeshogi List</title>
    <link href="CSS/all_pages.css" rel="stylesheet">
</head>
<body>
<a href = "slo_tsumeshogi.php"id = "backButton">≪</a><br>
    <h1>榧野香一の詰将棋問題集</h1>
    <p>このページにある詰将棋は作者坂田慎吾5段様の許諾を頂いて提供しています。<br>
        「榧野香一の榧香る３手詰にゃ」という素晴らしい本から取ったので、<br>
        是非作者を支援して本の購入をご検討ください！<br><br>
        <a style='font-size: 5vw; color:chocolate;' href='https://www.amazon.co.jp/%E6%A6%A7%E9%87%8E%E9%A6%99%E4%B8%80%E3%81%AE%E6%A6%A7%E9%A6%99%E3%82%8B%EF%BC%93%E6%89%8B%E8%A9%B0%E3%81%AB%E3%82%83-%E5%9D%82%E7%94%B0%E6%85%8E%E5%90%BE/dp/B09PHF9BM8'target='_blank' rel='noopener noreferrer'>アマゾンのリンクはこちら</a></p>
        <h3>詰将棋</h3>
    <?php 
    while($row = mysqli_fetch_array($result)){
        //list all of the tsume shogi problems
        echo "<a class = 'problemList' href = 'tsume.php?id=".$row['id']."'>🐱".$row['problemName']."</a> by <a style='color: blue; font-size: 4vw' href = 'https://twitter.com/kayanokoithi' target='_blank' rel='noopener noreferrer'> @kayanokoithi</a>";
    }//https://www.freecodecamp.org/news/how-to-use-html-to-open-link-in-new-tab/