<?php

require_once SHAREDPATH . 'template.php';

begin_html_page('');
?>

<a id="backButton" href="/new-game">≪</a>
<h1>オープン対戦を作成</h1>

<form action="/new-game/new-open-game" name="openGameData" method="post">
  <h3>ユーザーの色</h3>
  <input type="radio" id="userColorBlack" name="userColor" value="blackplayer">
  <label for="userColorBlack">黒</label><br>
  <input type="radio" id="userColorWhite" name="userColor" value="whiteplayer">
  <label for="userColorWhite">白</label><br>
  <input type="submit" value="対戦作成">
</form>
<?php
end_html_page();
