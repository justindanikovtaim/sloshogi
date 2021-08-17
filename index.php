<?php 
    if(isset($_COOKIE['current_user_cookie'])){
        header('Location: user_page.php');
        die();
    }?>
    
<!DOCTYPE HTML>
<head>
    <meta charset="utf-8">
    <title>Slo Shogi</title>
    
    <link href="CSS/all_pages.css" rel="stylesheet">

</head>

<body>

    <h1>SLO SHOGI</h1>
    <br>
    <h2><a href = "user_login.html">ユーザーログインはこちら</a></h2>
    <br>
    <h3>SLO将棋とは？ | What is SLO SHOGI?</h3>
    <h2>将棋対局サーバー | A Shogi Game Server</h2>
    
    <p>SLO将棋はゆっくりとできる将棋のサーバーです。好きなペースで手を指して、対局を同時に何個も参加できます。</p><p>
        SLO SHOGI is a slow-paced shogi server. You can take as much time as you need to make a move and play multiple games at once.
    </p>
    <h2>時間がないけど、将棋したい！｜I Don't Have Time, But I Want to Play!</h2>
    <P>
    電車に乗っている時とか、少しの休憩の時など、普通の対局をする時間がないけど将棋がしたい時、SLO将棋がピッタリです。</p><p>
    SLO SHOGI is perfect for times when you want to play shogi but you don't have enough time to play a full match, such as when riding the train or taking a quick break from work.
    </P>
    <h2>アカウント作成は無料｜Making an Account Is Free</h2>
    <p>
        アカウント作成は無料、簡単、そしてメールアドレス以外に個人情報を一切問いません。</p><p>
        Making an account it free, easy, and doesn't require any personal information other than an email address.
</p>
<h2><a href = "#">アカウント作成</a></h2>
<h2><a href = "#">Make an Account</a></h2>


</body>