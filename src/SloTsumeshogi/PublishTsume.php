<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';

$id = $_GET['id'];
$pUp = $_GET['pUp'];

$query = "UPDATE tsumeshogi SET published = ? WHERE id = ?";

begin_html_page('Publish Slo Tsumeshogi');

if (safe_sql_query($query, ['is', $pUp, $id])) : ?>
    <h1>詰将棋問題の公開・非公開状況は更新された</h1><br>
    <a href="/slo-tsumeshogi">詰将棋ページへ移動　Go to tsumeshogi page</a>
<?php else : ?>
    サーバーへの接続が失敗しました。後でもう一回試してください　Error connecting to the server. Please try again later<br>
    <a href="/slo-tsumeshogi">詰将棋ページへ移動　Go to tsumeshogi page</a>
<?php endif; ?>
<?php end_html_page(); ?>
