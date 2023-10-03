<?php
require_once SHAREDPATH . 'template.php';

begin_html_page('Slo Tsumeshogi List', ['menus.css']);
?>
<a href="/user-page" id="backButton">≪</a><br>
<br><br>
<h1>SLO詰将棋へようこそ</h1>
<br>
<button class="menuButton"><a href="/slotsumeshogi/all-tsume">全問題を表示</a></button>
<br><br>
<button class="menuButton"><a href="/slotsumeshogi/pro-tsume">坂田慎吾5段作</a></button>
<br><br>
<button class="menuButton"><a href="/slotsumeshogi/my-tsume">自作問題を表示</a></button>
<br><br>
<button class="menuButton"><a href="/slotsumeshogi/initialize-tsume?new=yes">問題を新規作成</a></button>
<?php end_html_page() ?>
