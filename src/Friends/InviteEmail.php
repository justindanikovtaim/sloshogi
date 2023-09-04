<?php
require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';

begin_html_page("SLO Shogi Invite Friends");
?>

<a id="backButton" href="/friends">≪</a>
<br>
<br>
<h3>友達へ招待リンクを送ります Send and invite link to a friend</h3>
<form action="/friends/send-invite" method="post">
    <label for="email">友達のアドレス Friend's Email</label><br>
    <input name="email" type="email"><br>
    <label for="msg">友達へのメッセージ Message for your friend</label>
    <input name="msg" type="text">
    <input type="submit" value="送信　Send">
</form>

<?php
end_html_page();
