<?php
require 'connect.php';
$id = $_GET['id'];
$pUp = $_GET['pUp'];

$query = "UPDATE tsumeshogi SET published = '$pUp' WHERE id = '$id'";


?>
<!DOCTYPE html>
<head>
    <meta charset = "utf-8">
    <title>Publish Slo tsumeshogi</title>
    <link href="CSS/all_pages.css"  rel="stylesheet">
</head>
<body>
<?php

if(mysqli_query($link, $query)){

echo "<h1>詰将棋問題の公開・非公開状況は更新された</h1><br>";
echo "<a href = 'slo_tsumeshogi.php'>詰将棋ページへ移動　Go to tsumeshogi page</a>";


}else{
echo mysqli_error($link);
echo "サーバーへの接続が失敗しました。後でもう一回試してください　Error connecting to the server. Please try again later<br>";
echo "<a href = 'slo_tsumeshogi.php'>詰将棋ページへ移動　Go to tsumeshogi page</a>";
}

?>