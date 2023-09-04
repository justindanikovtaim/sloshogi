<?php
require_once SHAREDPATH . 'template.php';

begin_html_page('Slo Shogi Private Game', ['all_pages.css']);
?>

<h1>非公開対局です This is a private game</h1>
<br>
<a href="/user-page">ユーザーページへ　To User Page</a>

<?php
end_html_page();
