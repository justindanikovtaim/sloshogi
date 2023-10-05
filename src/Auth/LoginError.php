<?php
require_once SHAREDPATH . 'template.php';

begin_html_page('Slo Shogi Login Error');
?>

<a id="backButton" href="/login">≪</a>
<h2>
    <br><br>
    ユーザー名またはパスワードが違います。再度ログインしてください
    <br>
    Invalid username or password. Please try again.
</h2>

<?php
end_html_page();
