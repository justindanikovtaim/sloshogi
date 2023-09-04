<?php

require_once SHAREDPATH . 'template.php';

begin_html_page('Slo Shogi Login');
?>

<h1>SLO 将棋</h1>
<br>
<form action="/verify-login" method="post">
    <label for="username"><b>ユーザー名/Username</b></label>
    <input type="text" placeholder="Enter Username" name="userData" required>
    <br>
    <label for="pw"><b>パスワード/Password</b></label>
    <input type="password" placeholder="Enter Password" name="pw" required>
    <br>
    <button type="submit">ログイン/Login</button>
</form>
<br>
<br>
<a href="/forgot-password" style="font-size: 4vw;">パスワード忘れた Forgot Password</a>

<?php
end_html_page();
