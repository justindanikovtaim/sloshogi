<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

$currentUser = getCurrentUser();

$currentGameQuery = "SELECT id FROM gamerecord WHERE ( blackplayer = ? OR whiteplayer = ?) AND (status = 2 OR (status = 4 AND winner != ? ) OR (status = 5 AND winner = ?))";

$getCurrentGameId = safe_sql_query($currentGameQuery, ['ssss', $currentUser, $currentUser, $currentUser, $currentUser]);
$currentGameIdArray =  [];
while ($row = mysqli_fetch_array($getCurrentGameId)) {
    array_push($currentGameIdArray, $row['id']); //add each gameid related to the user to an array
}
$opponentNameArray = [];
for ($i = 0; $i < sizeof($currentGameIdArray); $i++) {
    $getOpponent = safe_sql_query("SELECT blackplayer, whiteplayer, turn FROM gamerecord WHERE id = ?", ['i', $currentGameIdArray[$i]]);
    $getOpponentArray = mysqli_fetch_array($getOpponent);

    if ($getOpponentArray['blackplayer'] == $currentUser) {

        array_push($opponentNameArray, $getOpponentArray['whiteplayer']);

        if ($getOpponentArray['turn'] % 2 == 0) { //see if it is even turn (white)
            array_push($opponentNameArray, 0); //add on a 0 for false, beause it is not the player's turn
        } else {
            array_push($opponentNameArray, 1); //add on a 1 for true becase it is the player's turn
        }
    } else {
        array_push($opponentNameArray, $getOpponentArray['blackplayer']);
        //for some reason, the next line isn't working when it's the first turn
        if ($getOpponentArray['turn'] % 2 != 0) { //if it's the first turn or black's turn
            array_push($opponentNameArray, 0); //add on a 0 for false, beause it is not the player's turn
        } else {
            array_push($opponentNameArray, 1); //add on a 1 for true becase it is the player's turn
        }
    }
}

$getNewChallenges = safe_sql_query("SELECT id FROM gamerecord WHERE status = 1 AND creator != ? AND ( blackplayer = ? OR whiteplayer = ?)", ['sss', $currentUser, $currentUser, $currentUser]);
//get challenges that the user didn't create themselves
$challengesIdArray =  [];
while ($row = mysqli_fetch_array($getNewChallenges)) {
    array_push($challengesIdArray, $row['id']); //add each gameid related to the user to an array
}
$challengingOpponentArray = [];
for ($i = 0; $i < sizeof($challengesIdArray); $i++) {
    $getOpponent = safe_sql_query("SELECT blackplayer, whiteplayer FROM gamerecord WHERE id = ?", ['s', $challengesIdArray[$i]]);
    $getOpponentArray = mysqli_fetch_array($getOpponent);
    if ($getOpponentArray['blackplayer'] == $currentUser) {

        array_push($challengingOpponentArray, $getOpponentArray['whiteplayer']);
    } else {
        array_push($challengingOpponentArray, $getOpponentArray['blackplayer']);
    }
}

$getUserInfo = safe_sql_query("SELECT * FROM users WHERE username = ?", ['s', $currentUser]);
$userInfoArray = mysqli_fetch_array($getUserInfo);

begin_html_page('User Page', ['user_page.css']);
?>

<script>
    let currentGameIdArray = <?php echo json_encode($currentGameIdArray); ?>;
    let currentGameOpponentArray = <?php echo json_encode($opponentNameArray); ?>;
    let newChallengesArray = <?php echo json_encode($challengesIdArray); ?>;
    let challengesOpponentArray = <?php echo json_encode($challengingOpponentArray); ?>;
</script>

<div id="all">
    <div id="nameIconRating">
        <h1 id="userName"><?= $currentUser ?></h1>
        <h2 id="rating">段級: ?</h2>
        <h2 id="record"><?= $userInfoArray['record'] ?>&nbsp&nbsp </h2>
        <p id="hitokotoInput">"<?= $userInfoArray['hitokoto'] ?>"</p>
        <a href="/settings" id="settings">設定</a>
        <div id="iconBox">
            <img src="/public/images/icons/<?= $_COOKIE['icon'] ?>_icon.png" id="userIcon">
        </div>
    </div>


    <div class="user">
        <h3 class='centered'>対局中</h3>
        <br>

        <div id="allGames"></div>
    </div>
    <div class="user">
        <h3 class='centered'>新着チャレンジ</h3>
        <br>

        <div id="newChallenges"></div>
    </div>

    <div class='buttonRow'>
        <a href="/new-game"><button class="bigMenuButton">新規対局</button></a>
    </div>
    <br><br>
    <div class='buttonRow'>
        <a href="/friends"><button class="medMenuButton">友達</button></a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href='finished-games'><button class="medMenuButton">過去対局</button></a>
    </div>
    <br>
    <h1><a style="font-size: 7vw; color: blue" href="/forum/forum-top">掲示板</h1>
    <h1><a href="/slotsumeshogi">詰将棋（β版）</a></h1>
    <h1><a href="/feedback-form?src=user_page&id=na" class='logoutButton'>バグ報告</a></h1>
    <h1><a href="/logout" class="logoutButton">ログアウトLog Out</a></h1>
</div>

<script src="/public/js/get_games.js"></script>

<?php
end_html_page();
