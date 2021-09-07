<?php
$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
$getUserInfo = mysqli_query($link, "SELECT * FROM users WHERE username = '".$_COOKIE['current_user_cookie']."'");
$userInfoArray = mysqli_fetch_array($getUserInfo);

?>

<!DOCTYPE HTML>
<head>
    <link href="CSS/all_pages.css" rel="stylesheet">
    <link href="CSS/user_page.css" rel="stylesheet">

    <script>
        function showPassReset(){
            document.getElementById("passForm").style.visibility = "visible";
        }
        </script>
 </head>
 <body>
 <a id = "backButton" href = "user_page.php">≪</a>
 <br>
 <br>

     <div id = "nameIconRating">
<h1 class = "floatLeft"><?=$_COOKIE['current_user_cookie']?> </h1>
<h2 class = "floatLeft">段級: <?=$userInfoArray['rating']?></h2>
<h2 class = "floatLeft">勝敗レコード: <?=$userInfoArray['record']?>&nbsp&nbsp <a href = "update_icon.php" id = "settings">更新　Change</a></h2>

</div>

<a href ="update_icon.php"><img src= "images/icons/<?=$_COOKIE['icon']?>_icon.png" id = "userIcon"></a>

<!--need to add password reset field here -->
<br><br>
<a href= "#" onclick = "showPassReset()" class = "gameURL">パスワード変更 Change Password</a>
<br>
<br>
<form id = "passForm" action = 'reset_password.php' method ='post'>
<label for='oldPass' class = "formLabel"><b>現在のパスワード Current Password</b></label>
<input type = 'password' placeholder='Old Password' name = 'oldPass' required>
<br>
    <label for='pass' class = "formLabel"><b>新パスワード New Password</b></label>
    <input title='3~20文字｜3-20 characters'
   pattern = '{3,20}' type = 'password' placeholder='New Password' name = 'pass' maxlength = '20' required>
    <br>
    <label for = 'confirmPass' class = "formLabel"><b>パスワード再入力/Re-enter Password</b></label>
    <input type = 'password'  pattern = '{3,20}' placeholder = 'Confirm Password' name ='confirmPass' required>
    <br>
    <button type = 'submit' id = "submitButton">更新/Update</button>
    </form>

<h1><a href = "logout.php" id = logoutButton>ログアウトLog Out</a></h1>