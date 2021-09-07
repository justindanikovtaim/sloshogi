<?php
$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
  }

$enteredPass1 = htmlspecialchars($_POST['pass']);
$enteredPass2 = htmlspecialchars($_POST['confirmPass']);
$OTP = htmlspecialchars($_POST['OTP']);


if($enteredPass1 != $enteredPass2){
    echo "パスワードが異なる　Passwords do not match <br>";
    echo "<a href = 'account_setup.php?OTP=".$OTP."'>戻る　Go Back</a>";
}else{
    $result = mysqli_query($link, "SELECT * FROM newaccounts WHERE OTP =".$OTP);
    //get the user's email and username based on the OTP
    $getNewAccountInfo = mysqli_fetch_array($result);
    $newEmail = $getNewAccountInfo['email'];
    $newUsername = $getNewAccountInfo['username'];
    $enteredPass1 = password_hash($enteredPass1, PASSWORD_DEFAULT);

    $addUserQuery = "INSERT INTO users (username, email, pass) VALUES ('".$newUsername."', '".$newEmail."', '".$enteredPass1."')";
    if(mysqli_query($link, $addUserQuery)){

        setcookie('current_user_cookie', $newUsername, time() + (86400 * 30), "/");//set the cookie so they can visit the user page 
        echo "アカウントが確定されました Your account has been confirmed <br>";
        echo "<a href = 'user_page.php'>ユーザーページへ移動　Go to your user page</a>";
        
        //delete the record from the newaccounts table
        mysqli_query($link, "DELETE FROM newaccounts WHERE OTP =".$OTP);

    }else{
       echo mysqli_error($link);
        echo "サーバーへの接続が失敗しました。後でもう一回試してください　Error connecting to the server. Please try again later<br>";
        echo "<a href = 'account_setup.php?OTP=".$OTP."'>戻る Back</a>";
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