<?php
require 'connect.php';
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
  }
$hashQuery = mysqli_query($link, "SELECT pass FROM users WHERE username = '".$_COOKIE['current_user_cookie']."'");
$passHash = mysqli_fetch_array($hashQuery);

$oldPass = htmlspecialchars($_POST['oldPass']);
$enteredPass1 = htmlspecialchars($_POST['pass']);
$enteredPass2 = htmlspecialchars($_POST['confirmPass']);

if($enteredPass1 != $enteredPass2){
    echo "パスワードが異なる　Passwords do not match <br>";
    echo "<a href = 'settings.php'>戻る　Go Back</a>";
}else if(!password_verify($oldPass, $passHash['pass'])){
    echo "現在のパスワードが違います current password incorrect <br>";
    echo "<a href = 'settings.php'>戻る　Go Back</a>";
}else{
    $enteredPass1 = password_hash($enteredPass1, PASSWORD_DEFAULT);

    $passwordQuery = "UPDATE users SET pass = '".$enteredPass1."' WHERE username = '".$_COOKIE['current_user_cookie']."'";
    if(mysqli_query($link, $passwordQuery)){

        echo "<h2>パスワードが更新されました Your password has been updated <br>";
        echo "<a href = 'user_page.php'>ユーザーページへ移動　Go to your user page</a></h2>";
        
    }else{
       echo mysqli_error($link);
        echo "サーバーへの接続が失敗しました。後でもう一回試してください　Error connecting to the server. Please try again later<br>";
        echo "<a href = 'settings.php'>戻る Back</a>";
    }

} 


    ?>

<!DOCTYPE HTML>
<head>
    <meta charset="utf-8">
    <title>Slo Shogi Create Finalize Account</title>
    
    <link href="CSS/all_pages.css" rel="stylesheet">

</head>

<body>
</body>