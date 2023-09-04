<?php
require_once SHAREDPATH . 'template.php';

begin_html_page('Slo Shogi Account Creation', ['user_page.css']);
?>

<h1>SLO 将棋　アカウント登録</h1>
<br>
<form action="/verify-creation" method="post" class="centered">
    <label for="userData"><b>希望ユーザー名</b></label>
    <input title="3~20文字；数字、A-Z、_, - のみ；スペース禁止｜4-20 characters; numbers, letters, _, - only; no spaces" pattern="[A-Za-z0-9\-_\.]{4,20}" type="text" placeholder="Enter Username" name="userData" maxlength="20" required>
    <br><br>
    <label for="address"><b>メールアドレス</b></label>
    <input type="email" placeholder="youremail@website.com" name="address" autocomplete="on" required>
    <br>
    <label for="confirmAddress"><b>アドレス 確認</b></label>
    <input type="email" placeholder="youremail@website.com" name="confirmAddress" autocomplete="off" required>
    <br>
    <br>
    <div class="buttonRow">
        <button type="submit" class="bigMenuButton">決定</button>
    </div>
</form>

<?php
end_html_page();
