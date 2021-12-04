<?php
require 'connect.php';

  $boardConfig = $_POST['boardConfig'];
  $problemName = $_POST['problemName'];
  $mochigomaConfig = $_POST['mochigomaConfig'];
  $moveSequence = $_POST['moveSequence'];
  $createdBy = $_COOKIE['current_user_cookie'];

$query = "INSERT INTO tsumeshogi (problemName, boardSetup, mochigomaSetup, mainSequence, createdBy) VALUES ('$problemName', '$boardConfig', '$mochigomaConfig', '$moveSequence', '$createdBy')";

?>
<!DOCTYPE html>
<head>
    <meta charset = "utf-8">
    <title>Save Slo tsumeshogi</title>
    <link href="CSS/all_pages.css"  rel="stylesheet">
</head>
<body>
<?php
if(mysqli_query($link, $query)){

    echo "<h1>詰将棋問題は保存されました</h1><br>";
    echo "<a href = 'slo_tsumeshogi.php'>詰将棋ページへ移動　Go to tsumeshogi page</a>";
    

}else{
   echo mysqli_error($link);
    echo "サーバーへの接続が失敗しました。後でもう一回試してください　Error connecting to the server. Please try again later<br>";
    echo "<a href = 'slo_tsumeshogi.php'>詰将棋ページへ移動　Go to tsumeshogi page</a>";
}
?>
</body>