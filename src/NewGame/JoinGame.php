<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

$getOpenGameId = safe_sql_query("SELECT id FROM gamerecord WHERE status = 1 AND creator != ?", ['s', getCurrentUser()]);
$openGameIdArray =  [];
while ($row = mysqli_fetch_array($getOpenGameId)) {
    array_push($openGameIdArray, $row['id']); //add each open game id that user didn't make
}
$opponentNameArray = [];
for ($i = 0; $i < sizeof($openGameIdArray); $i++) {
    $getOpponent = safe_sql_query("SELECT creator FROM gamerecord WHERE id = ?", ['i', $openGameIdArray[$i]]);
    $getOpponentArray = mysqli_fetch_array($getOpponent);
    array_push($opponentNameArray, $getOpponentArray['creator']);
}

begin_html_page('');
?>

<a id="backButton" href="/new-game">≪</a>
<h1>Open Games</h1>
<div id="drawOpenGames"></div>

<script>
    let getOpenGamesArray = <?php echo json_encode($openGameIdArray); ?>;
    let currentGameOpponentArray = <?php echo json_encode($opponentNameArray); ?>;
    let openGamesArray = [];

    for (i = 0; i < getOpenGamesArray.length; i++) {
        openGamesArray[i] = document.createElement("a");
        openGamesArray[i].href = "/new-game/join-open-game?id=" + getOpenGamesArray[i];
        openGamesArray[i].innerHTML = "SLO" + getOpenGamesArray[i] + " vs. " + currentGameOpponentArray[i];
        document.getElementById("drawOpenGames").appendChild(openGamesArray[i]);
        let lineBreak = document.createElement("br");
        document.getElementById("drawOpenGames").appendChild(lineBreak);
    }
</script>

<?php end_html_page() ?>
