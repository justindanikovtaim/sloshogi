<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'session.php';
require_once SHAREDPATH . 'template.php';

$getNewChallenges = safe_sql_query("SELECT id FROM gamerecord WHERE status = 1 AND creator = ? AND
( blackplayer = ? OR whiteplayer = ?)", ['s', getCurrentUser(), getCurrentUser(), getCurrentUser()]);
//get challenges that the user created themselves
$sentChallengesIdArray =  [];
while ($row = mysqli_fetch_array($getNewChallenges)) {
    array_push($sentChallengesIdArray, $row['id']); //add each gameid related to the user to an array
}
$sentChallengingOpponentArray = [];
for ($i = 0; $i < sizeof($sentChallengesIdArray); $i++) {
    $getOpponent = safe_sql_query("SELECT blackplayer, whiteplayer FROM gamerecord WHERE id = ?", ['s', $sentChallengesIdArray[$i]]);
    $getOpponentArray = mysqli_fetch_array($getOpponent);
    if ($getOpponentArray['blackplayer'] == null || $getOpponentArray['whiteplayer'] == null) {
        //if one of the players is null, it's an open challenge
        array_push($sentChallengingOpponentArray, "? Open Game");
    } else if ($getOpponentArray['blackplayer'] == getCurrentUser()) {

        array_push($sentChallengingOpponentArray, $getOpponentArray['whiteplayer']);
    } else {
        array_push($sentChallengingOpponentArray, $getOpponentArray['blackplayer']);
    }
}

$getNewChallenges = safe_sql_query("SELECT id FROM gamerecord WHERE status = 1 AND creator != ? AND
( blackplayer = ? OR whiteplayer = ?)", ['s', getCurrentUser(), getCurrentUser(), getCurrentUser()]);
//get challenges that the user didn't create themselves
$recievedChallengesIdArray =  [];
while ($row = mysqli_fetch_array($getNewChallenges)) {
    array_push($recievedChallengesIdArray, $row['id']); //add each gameid related to the user to an array
}
$recievedChallengingOpponentArray = [];
for ($i = 0; $i < sizeof($recievedChallengesIdArray); $i++) {
    $getOpponent = safe_sql_query("SELECT blackplayer, whiteplayer FROM gamerecord WHERE id = ?", ['s', $recievedChallengesIdArray[$i]]);
    $getOpponentArray = mysqli_fetch_array($getOpponent);
    if ($getOpponentArray['blackplayer'] === getCurrentUser()) {
        array_push($recievedChallengingOpponentArray, $getOpponentArray['whiteplayer']);
    } else {
        array_push($recievedChallengingOpponentArray, $getOpponentArray['blackplayer']);
    }
}

$getOpenGameId = safe_sql_query("SELECT id FROM gamerecord WHERE status = 1 AND creator != ?", ['s', getCurrentUser()]);
$openGameIdArray =  [];
while ($row = mysqli_fetch_array($getOpenGameId)) {
    array_push($openGameIdArray, $row['id']); //add each open game id that user didn't make
}
$opponentNameArray = [];
for ($i = 0; $i < sizeof($openGameIdArray); $i++) {
    $getOpponent = safe_sql_query("SELECT creator FROM gamerecord WHERE id = ?", ['s', $openGameIdArray[$i]]);
    $getOpponentArray = mysqli_fetch_array($getOpponent);
    $getOpRating = safe_sql_query("SELECT rating FROM users WHERE username = ?", ['s', $getOpponentArray['creator']]);
    $opRating = mysqli_fetch_array($getOpRating);
    array_push($opponentNameArray, $getOpponentArray['creator'] . "(" . $opRating['rating'] . ")");
}

begin_html_page('', ['all_pages.css', 'user_page.css']);

?>

<a id="backButton" href="user_page.php">≪</a>
<h1>新規対局</h1>
<div class='buttonRow'>
    <a href="/new-challenge"><button class="bigMenuButton">&nbsp;友達と対局&nbsp;</button></a>
</div>
<br><br>
<div class='buttonRow'>
    <a href="/new-game/create-open-game"><button class="bigMenuButton">オープン対局を作る</button></a>
</div>
<br><br>

<div class="user">
    <h1>オープン対局</h1>
    <div id="drawOpenGames"></div>
</div>

<div class="user">
    <h1>承認待ち対局</h1>
    <div id="sentChallenges"></div>
</div>

<script>
    let sentChallengesArray = <?php echo json_encode($sentChallengesIdArray); ?>;
    let sentChallengesOpponentArray = <?php echo json_encode($sentChallengingOpponentArray); ?>;
    let recievedChallengesArray = <?php echo json_encode($recievedChallengesIdArray); ?>;
    let recievedChallengesOpponentArray = <?php echo json_encode($recievedChallengingOpponentArray); ?>;

    let getOpenGamesArray = <?php echo json_encode($openGameIdArray); ?>;
    let currentGameOpponentArray = <?php echo json_encode($opponentNameArray); ?>;
    let openGamesArray = [];

    for (i = 0; i < getOpenGamesArray.length; i++) {
        openGamesArray[i] = document.createElement("a");
        openGamesArray[i].href = "/new-game/join-open-game?id=" + getOpenGamesArray[i];
        openGamesArray[i].innerHTML = "vs. " + currentGameOpponentArray[i];
        document.getElementById("drawOpenGames").appendChild(openGamesArray[i]);
        let lineBreak = document.createElement("br");
        document.getElementById("drawOpenGames").appendChild(lineBreak);
    }
</script>

<script src="/public/js/get_challenges.js"></script>
<?php end_html_page() ?>
