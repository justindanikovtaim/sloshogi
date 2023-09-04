<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

$user = getCurrentUser();

$getPastGameId = safe_sql_query("SELECT id FROM gamerecord WHERE (status = 3 OR (status = 4 AND winner = ?) OR (status = 5 AND winner != ?) ) AND ( blackplayer = ? OR whiteplayer = ? )", ['ssss', $user, $user, $user, $user]);

$pastGameIdArray =  [];
while ($row = mysqli_fetch_array($getPastGameId)) {
    array_push($pastGameIdArray, $row['id']); //add each gameid related to the user to an array
}
$pastOpponentNameArray = [];
for ($i = 0; $i < sizeof($pastGameIdArray); $i++) {
    $getOpponent = safe_sql_query("SELECT blackplayer, whiteplayer FROM gamerecord WHERE id = ?", ['i', $pastGameIdArray[$i]]);
    $getOpponentArray = mysqli_fetch_array($getOpponent);
    if ($getOpponentArray['blackplayer'] == $user) {
        array_push($pastOpponentNameArray, $getOpponentArray['whiteplayer']);
    } else {
        array_push($pastOpponentNameArray, $getOpponentArray['blackplayer']);
    }
}

$getUserInfo = safe_sql_query("SELECT * FROM users WHERE username = ?", ['s', $user]);
$userInfoArray = mysqli_fetch_array($getUserInfo);

begin_html_page("SLO Shogi User Page", ['user_page.css']);
?>
<script>
    let pastGameIdArray = <?php echo json_encode($pastGameIdArray); ?>;
    let pastGameOpponentArray = <?php echo json_encode($pastOpponentNameArray); ?>;
</script>

<a id="backButton" href="/user-page">≪</a>
<br><br>

<div id="all">
    <div id="nameIconRating">
        <h1 id="userName"><?= $user ?></h1>
        <h2 id="rating">段級: ?</h2>
        <h2 id="record"><?= $userInfoArray['record'] ?>&nbsp&nbsp </h2>
        <p id="hitokotoInput">"<?= $userInfoArray['hitokoto'] ?>"</p>
        <a href="/settings" id="settings">設定 Settings</a>
        <div id="iconBox">
            <img src="/public/images/icons/<?= $_COOKIE['icon'] ?>_icon.png" id="userIcon">
        </div>
    </div>


    <div class="user">
        <h3>Finished Games</h3>
        <div id="finishedGames"></div>
    </div>
    <br>
    <h1><a href="/feedback-form?src=user_page&id=finishedGames">バグ報告・Report a bug</a></h1>
    <h1><a href="/slo_tsumeshogi">詰将棋（β版）</a></h1>
    <h1><a href="/logout" id="logoutButton">ログアウトLog Out</a></h1>

</div>
<script src="/public/js/get_finished_games.js"></script>

<?php
end_html_page();
