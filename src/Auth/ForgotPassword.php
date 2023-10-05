<?php
require_once SHAREDPATH . 'template.php';

begin_html_page('Slo Shogi Forgotten Password');
?>

<h1 style="font-size: 8vw;">パスワードのリセット
    <br>Reset Password
</h1>
<form id="enterEmailForm" action="/email-temp-password" method="post">
    <label for="enterEmail">メールアドレス Email</label>
    <input type="email" id="enterEmail" name="email"><br><br>
    <input type="submit" value="送信　Send">
</form>

<?php
end_html_page();
