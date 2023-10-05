<?php

require_once SHAREDPATH . 'template.php';

begin_html_page('What is Slo Shogi', ['index.css']);

?>

<a id="backButton" href="/">≪<span style="font-size: 6vw;color: grey;">トップへ戻る</span></a><br><br>

<h1>SLO将棋とは？</h1>

<h2>将棋対局サーバー</h2>

<p class='paragraphText'>SLO将棋はゆっくりとできる将棋のサーバーです。好きなペースで手を指して、対局を同時に何個も参加できます。</p>

<h2>時間がないけど、将棋したい！</h2>
<P class='paragraphText'>電車に乗っている時とか、少しの休憩の時など、普通の対局をする時間がないけど将棋がしたい時、SLO将棋がピッタリです。</p>

<h2>アカウント作成は無料</h2>
<p class='paragraphText'>アカウント作成は無料、簡単、そしてメールアドレス以外に個人情報を一切問いません。</p>

<h2><a href="/new-account">アカウント作成</a></h2>

<h3><a href="/feedback-form?src=index&id=na" id="feedback">バグ報告・Report a bug</a></h3>

<?php
end_html_page();
