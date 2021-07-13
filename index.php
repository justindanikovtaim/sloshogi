<?php 
    if(isset($_COOKIE['current_user_cookie'])){
        header('Location: user_page.php');
        die();
    }?>
    
<!DOCTYPE HTML>
<head>
    <meta charset="utf-8">
    <title>Slo Shogi</title>
    
    <!--<link href="CSS/index_style_sheet.css" rel="stylesheet"> -->

    <script>
        function submitPostLink(){
            document.postlink.submit();
        }
    </script>

</head>

<body>

    <h1>SLO 将棋-Shogi-</h1>
    <br>
    <form action = "verify_login.php" method ="post">
        <label for="username"><b>ユーザー名/Username</b></label>
        <input type = "text" placeholder="Enter Username" name = "userData" required>
        <br>
        <label for = "pw"><b>パスワード/Password</b></label>
        <input type = "password" placeholder = "Enter Password" name ="pw" required>
        <br>
        <button type = "submit">ログイン/Login</button>
    </form>
</body>
