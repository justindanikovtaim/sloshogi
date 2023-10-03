<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

$currentUser = getCurrentUser();

if ($_POST["userColor"] == "blackplayer") {
    $blackPlayer = $currentUser;
    $whitePlayer = $_POST["opponent"];
} else {
    $whitePlayer = $currentUser;
    $blackPlayer = $_POST["opponent"];
}
$publicPrivate = $_POST["publicPrivate"];

$newChallenge = 'INSERT INTO gamerecord (private, moves, blackplayer, whiteplayer, status, creator)
     VALUES (?, "", ?, ?, "1", ?);';

$getActiveGames = safe_sql_query("SELECT id FROM gamerecord WHERE (blackplayer = ? OR whiteplayer = ?) AND (status = 1 OR status = 2)", ['ss', getCurrentUser(), getCurrentUser()]);

$activeGameCount = 0;
while ($sqlArrayHolder = mysqli_fetch_array($getActiveGames)) { //this loop goes though the user's active games and increments the counter for each
    $activeGameCount++;
}
$getUserLevel = safe_sql_query("SELECT user_level FROM users WHERE username = ?", ['s', getCurrentUser()]);
$userLevel = mysqli_fetch_array($getUserLevel);

begin_html_page('Slo Shogi Challenge');
?>

<a id="backButton" href="/new-game">≪</a>
<h1>
    <?php
    if ($activeGameCount < 5  || $userLevel['user_level'] > 0) {
        if (safe_sql_query($newChallenge, ['isss', $publicPrivate, $blackPlayer, $whitePlayer, getCurrentUser()])) {
            echo "Challenge Sent!";
        }
    } else {
        echo "既に５つの対戦に参加しているため、新しい対戦を作ることはできません<br> You already have 5 active games/challenges. You cannot create a new challenge.";
    }

    ?>
</h1>

<?php end_html_page(); ?>
