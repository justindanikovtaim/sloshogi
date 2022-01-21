<?php
require 'connect.php';

  $boardConfig = $_POST['boardConfig'];
  $problemName = $_POST['problemName'];
  $mochigomaConfig = $_POST['mochigomaConfig'];
  $moveSequence = $_POST['moveSequence'];
  $timeLimit = $_POST['timeLimit'];
  $createdBy = $_COOKIE['current_user_cookie'];
  if(isset($_GET['reSave'])){
      $temp = $_GET['reSave'];
      //need to just update the problem. WHERE finds both the id (stored in $_GET['reSave']) as well as the user's nusername, in case they changed the URL to a different problem
      $query = "UPDATE tsumeshogi SET problemName = '$problemName', boardSetup = '$boardConfig', mochigomaSetup = '$mochigomaConfig', mainSequence = '$moveSequence', timeLimit = '$timeLimit' WHERE id = '$temp' AND createdBy = '$createdBy'";
  }else{
     //this nonsense is needed in case a prolem is deleted. It resets the auto-increment to whatever the next number above the highest current id is
$lastRow = mysqli_query($link, "SELECT MAX(id) FROM tsumeshogi");
$result = mysqli_fetch_row($lastRow);
$number = $result[0];
mysqli_query($link, "ALTER TABLE tsumeshogi AUTO_INCREMENT = '".($number +1)."'");


$query = "INSERT INTO tsumeshogi (problemName, boardSetup, mochigomaSetup, mainSequence, createdBy, timeLimit) VALUES ('$problemName', '$boardConfig', '$mochigomaConfig', '$moveSequence', '$createdBy', '$timeLimit')";

  }

 
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