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
    <link href="CSS/index.css" rel="stylesheet">


</head>

<body>
<div id = "userLogin"><a href = "user_login.html" id = "userLoginText">ログイン Login</a></div>

    <h1>SLO SHOGI</h1>
    <img id="screenshot" src= "images/screenshot.jpg">
    <h3>SLO将棋はまだβ版です。</h3>
    <p>でも、一緒に対局しテストしてくれる仲間を募集しています。興味がある方、気軽に公式Twitterにて連絡ください！</p>
        <a href = "https://twitter.com/ShogiSlo" id="twitterContact">＠ShogiSlo</a></p>
<br>
<br>
<h1><a href="feedback_form.php?src=user_page&id=na" id = "feedback">バッグ報告・Report a bug</a></h1>

    <!--<h3>SLO将棋とは？</h3>
    <h3>What is SLO SHOGI?</h3>

    <h2>将棋対局サーバー</h2>
    
    <p>SLO将棋はゆっくりとできる将棋のサーバーです。好きなペースで手を指して、対局を同時に何個も参加できます。</p>
    <h2>A Shogi Game Server</h2>
<p>
        SLO SHOGI is a slow-paced shogi server. You can take as much time as you need to make a move and play multiple games at once.
    </p>
    <h2>時間がないけど、将棋したい！</h2>
    <P>
    電車に乗っている時とか、少しの休憩の時など、普通の対局をする時間がないけど将棋がしたい時、SLO将棋がピッタリです。</p>
    <h2>I Don't Have Time, But I Want to Play!</h2>
<p>
    SLO SHOGI is perfect for times when you want to play shogi but you don't have enough time to play a full match, such as when riding the train or taking a quick break from work.
    </P>
    <h2>アカウント作成は無料</h2>
    <p>
        アカウント作成は無料、簡単、そしてメールアドレス以外に個人情報を一切問いません。</p>
        <h2>Making an Account Is Free</h2>
<p>
        Making an account it free, easy, and doesn't require any personal information other than an email address.
</p>
<h2><a href = "new_account.html">アカウント作成</a></h2>
<h2><a href = "new_account.html">Make an Account</a></h2>
-->


</body>