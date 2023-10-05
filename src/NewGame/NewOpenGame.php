<?php

require SHAREDPATH . 'database.php';
require SHAREDPATH . 'session.php';
require SHAREDPATH . 'template.php';

function createNewGame($userColor)
{
    $currentUser = getCurrentUser();
    $newChallengeQuery = 'INSERT INTO gamerecord (moves, ' . $userColor . ', status, creator)
    VALUES ("", ?, "1", ?);';

    return safe_sql_query($newChallengeQuery, ['ss', $currentUser, $currentUser]);
}

function getActiveGamesCount()
{
    $getActiveGames = safe_sql_query("SELECT id FROM gamerecord WHERE
    (blackplayer = ? OR whiteplayer = ?)
    AND (status = 1 OR status = 2)", ['ss', getCurrentUser(), getCurrentUser()]);

    $activeGameCount = 0;
    while ($sqlArrayHolder = mysqli_fetch_array($getActiveGames)) {
        $activeGameCount++;
    }

    return $activeGameCount;
}

function getUserLevel()
{
    $getUserLevel = safe_sql_query("SELECT user_level FROM users WHERE username = ?", ['s', getCurrentUser()]);
    $userLevel = mysqli_fetch_array($getUserLevel);
    return $userLevel['user_level'];
}

$userColor = $_POST["userColor"];
$activeGameCount = getActiveGamesCount();
$userLevel = getUserLevel();

if ($activeGameCount < 5 || $userLevel > 0) {
    if (createNewGame($userColor)) {
        $message = "Open game created";
    } else {
        $message = "ERROR: Not able to create a new game. ";
    }
} else {
    $message = "既に５つの対戦に参加しているため、新しい対戦を作ることはできません<br> You already have 5 active games/challenges. You cannot create a new game.";
}

begin_html_page('Slo Shogi New Game');
?>

<h2>
    <a id="backButton" href="/new-game">≪</a><br><br>
    <?php echo $message; ?>
</h2>

<?php
end_html_page();
