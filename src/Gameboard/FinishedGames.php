<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

function getPastGameIds($user)
{
    return safe_sql_query("SELECT id FROM gamerecord WHERE (status = 3 OR (status = 4 AND winner = ?) OR (status = 5 AND winner != ?)) AND (blackplayer = ? OR whiteplayer = ?)", ['ssss', $user, $user, $user, $user]);
}

function getOpponentNames($pastGameIds, $user)
{
    $opponentNames = [];
    foreach ($pastGameIds as $gameId) {
        $result = safe_sql_query("SELECT blackplayer, whiteplayer FROM gamerecord WHERE id = ?", ['i', $gameId]);
        $opponentArray = mysqli_fetch_array($result);
        if ($opponentArray['blackplayer'] == $user) {
            $opponentNames[] = $opponentArray['whiteplayer'];
        } else {
            $opponentNames[] = $opponentArray['blackplayer'];
        }
    }
    return $opponentNames;
}

$user = getCurrentUser();
$pastGameIds = [];
$pastGameIdQuery = getPastGameIds($user);
while ($row = mysqli_fetch_array($pastGameIdQuery)) {
    $pastGameIds[] = $row['id'];
}

$pastOpponentNames = getOpponentNames($pastGameIds, $user);

$getUserInfo = safe_sql_query("SELECT * FROM users WHERE username = ?", ['s', $user]);
$userInfoArray = mysqli_fetch_array($getUserInfo);

begin_html_page("SLO Shogi User Page", ['user_page.css']);
?>

<script>
    let pastGameIdArray = <?php echo json_encode($pastGameIds); ?>;
    let pastGameOpponentArray = <?php echo json_encode($pastOpponentNames); ?>;
</script>

<a id="backButton" href="/user-page">≪</a>
<br><br>

<div id="all">
    <div id="nameIconRating">
        <h1 id="userName"><?php echo $user ?></h1>
        <h2 id="rating">段級: ?</h2>
        <h2 id="record"><?php echo $userInfoArray['record'] ?>&nbsp;&nbsp; </h2>
        <p id="hitokotoInput">"<?php echo $userInfoArray['hitokoto'] ?>"</p>
        <a href="/settings" id="settings">設定 Settings</a>
        <div id="iconBox">
            <img src="/public/images/icons/<?php echo $_COOKIE['icon'] ?>_icon.png" id="userIcon">
        </div>
    </div>

    <div class="user">
        <h3>Finished Games</h3>
        <div id="finishedGames"></div>
    </div>
    <br>
    <h1><a href="/feedback-form?src=user_page&id=finishedGames">バグ報告・Report a bug</a></h1>
    <h1><a href="/slo-tsumeshogi">詰将棋（β版）</a></h1>
    <h1><a href="/logout" id="logoutButton">ログアウトLog Out</a></h1>
</div>

<script src="/public/js/get_finished_games.js"></script>

<?php
end_html_page();
?>
