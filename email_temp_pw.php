<?php 
require 'connect.php';


$enteredEmail = htmlspecialchars($_POST['email']);

$emailOkay = filter_var($enteredEmail, FILTER_VALIDATE_EMAIL);//make sure email is in correct format

if($emailOkay){
    $getEmails = mysqli_query($link, "SELECT username FROM users WHERE email = '".$enteredEmail."'");
    if($getEmails -> num_rows > 0)
    {
    $emailsArray = mysqli_fetch_array($getEmails);
    if(isset($emailsArray['username'])){
        //if a mathching email can be found in the database
        $usernameToAddressTo = $emailsArray['username'];
    }   
}else{
    //if the email can't be found
    $emailOkay = false;
} 
}


?>

<!DOCTYPE html>
<head>
<link href="CSS/all_pages.css" rel="stylesheet">
</head>

<body>
<a id = "backButton" href = "user_login.html">≪ <span style = "font-size: 5vw">ログインへ Back to Login</span></a>
<br>
<br>
<?php 
if(!$emailOkay){
    echo "<br><br>入力したアドレスに誤りがありました。もう一回入力してみてください There was an error in the email address you entered. PLease try again.";
}else{
    //generate temp password
    $tempPass = random_int(100000, 999999);// generate 6-digit random int

    //send the email

    $message = "SLO Shogi　パスワードのリセット
    \n".$usernameToAddressTo."さん、
    \n仮パスワードはこれです：
    \n".$tempPass."
    \n
    \nSlo Shogi Password Reset
    \n".$usernameToAddressTo.",
    \nYour temporary password is:
    \n".$tempPass."
    \n
    \nログインしましたら、ユーザーページよりすぐ更新してください。
    \nAfter logging in, please update your password via the user page";
    
    $message = wordwrap($message, 70);

if(mail($enteredEmail, "SLO Shogiパスワードのリセット　Slo Shogi Password Reset", $message)){

echo "<br><br><h3>A temporary password has been sent to your email address<br>
仮パスワードがメールアドレスに送信されました</h3>";
//update with a temporary password
mysqli_query($link, "UPDATE users SET pass = '".$tempPass."' WHERE username = '".$usernameToAddressTo."'");

}else{
    echo "問題がありました。少し待ってからもう一回送ってみて下さい There was a problem sending the email. Please try again after waiting for a short bit";
}
}
?>
</body>
