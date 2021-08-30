<?php
session_start();
$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
  }

$OTP = htmlspecialchars($_GET['OTP']);

//delete old records from the newaccounts table
mysqli_query($link, "DELETE FROM newaccounts WHERE timeCreated < (NOW() - INTERVAL 60 MINUTE)");

//search for user's OTP and get their account name and email in the process
$result = mysqli_query($link, "SELECT * FROM newaccounts WHERE OTP =".$OTP);
if($result){
    //If everything is okay, set password, rating, photo etc.

    echo     "<form action = 'finalize_account.php' method ='post'>
    <label for='pass'><b>パスワード Password</b></label>
    <input title='4~20文字；数字、A-Z、_, - のみ；スペース禁止｜4-20 characters; numbers, letters, _, - only; no spaces' 
   pattern = '[A-Za-z0-9\-_\.]{4,20}' type = 'password' placeholder='Enter Password' name = 'pass' maxlength = '20' required>
    <br>
    <label for = 'confirmPass'><b>パスワード再入力/Re-enter Password</b></label>
    <input type = 'password' placeholder = 'confirm password' name ='confirmPass' required>
    <br>
    <input type = 'hidden' name = 'OTP' value = ".$_GET['OTP'].">
    <button type = 'submit'>アカウントを確定/Finalize Account</button>
</form>";


}else{

    echo "時間切れでアカウントが削除されました｜Your account has been deleted due to timeout";
    echo "作成をもう一回行ってください｜Please try making a new account again";
}



?>
<!DOCTYPE HTML>
<head>
    <meta charset="utf-8">
    <title>Slo Shogi Create New Account</title>
    
    <link href="CSS/all_pages.css" rel="stylesheet">

</head>

<body>
<a href = "index.php">トップに戻る・Return to Homepage</a>
</body>